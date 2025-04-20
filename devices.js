function openModal() {
    document.getElementById("register-modal").classList.remove("hidden");
}

function closeModal() {
    document.getElementById("register-modal").classList.add("hidden");
    document.getElementById("device-name").value = "";
    document.getElementById("owner-name").value = "";
    document.getElementById("mac-address").value = "";
}


async function addDevice() {
    const name = document.getElementById("device-name").value.trim();
    const owner = document.getElementById("owner-name").value.trim();
    const mac = document.getElementById("mac-address").value.trim();
    const macRegex = /^([0-9A-Fa-f]{2}[:-]){5}([0-9A-Fa-f]{2})$/;

    if (!name || !owner || !mac) {
        alert("Please fill in all fields.");
        return;
    }

    if (!macRegex.test(mac)) {
        alert("Invalid MAC address format.");
        return;
    }

    try {
        const response = await fetch("add_device.php", {
            method: "POST",
            headers: {
                "Content-Type": "application/json"
            },
            body: JSON.stringify({ name, owner, mac })
        });

        const result = await response.json();

        if (result.success) {
            alert("Device registered successfully!");
            closeModal();
            await renderDevices();  // ✅ Refresh table from backend after adding
        } else {
            alert("Failed to register device: " + (result.message || "Unknown error"));
        }
    } catch (error) {
        console.error("Error while adding device:", error);
        alert("Error occurred while registering the device.");
    }
}




async function toggleStatus(mac, el) {
    const newStatus = el.classList.contains("active") ? "inactive" : "active";
    try {
        const res = await fetch("update_status.php", {
            method: "POST",
            headers: { "Content-Type": "application/json" },
            body: JSON.stringify({ mac, status: newStatus })
        });

        const result = await res.json();
        if (result.success) {
            renderDevices();
        } else {
            alert(result.message || "Status update failed.");
        }
    } catch (err) {
        console.error("Status toggle failed:", err);
    }
}


async function deleteDevice(mac) {
    if (!confirm("Are you sure you want to delete this device?")) return;

    try {
        const res = await fetch("delete_device.php", {
            method: "POST",
            headers: { "Content-Type": "application/json" },
            body: JSON.stringify({ mac })
        });

        const result = await res.json();
        if (result.success) {
            renderDevices();
        } else {
            alert(result.message || "Delete failed.");
        }
    } catch (err) {
        console.error("Delete device failed:", err);
    }
}


async function renderDevices() {
    const list = document.getElementById("device-list");
    const searchTerm = document.getElementById("search").value.toLowerCase();
    list.innerHTML = "";

    try {
        const res = await fetch("get_devices.php");
        const devices = await res.json();

        devices
            .filter(device =>
                device.device_name.toLowerCase().includes(searchTerm) ||
                device.owner_name.toLowerCase().includes(searchTerm) ||
                device.mac_address.toLowerCase().includes(searchTerm)
            )
            .forEach(device => {
                const row = document.createElement("tr");
                row.className = "border-b hover:bg-gray-50";

                row.innerHTML = `
                    <td class="p-2">${device.device_name}<br><small class="text-gray-500">${device.mac_address}</small></td>
                    <td class="p-2">${device.owner_name}</td>
                    <td class="p-2"><span class="status ${device.status}" onclick="toggleStatus('${device.mac_address}', this)">${device.status.charAt(0).toUpperCase() + device.status.slice(1)}</span></td>
                    <td class="p-2">${device.last_active}</td>
                    <td class="p-2"><button onclick="deleteDevice('${device.mac_address}')" class="text-red-600 hover:underline">Delete</button></td>
                `;
                list.appendChild(row);
            });
    } catch (err) {
        console.error("Failed to load devices:", err);
    }
}


// Initialize the page when it loads
document.addEventListener("DOMContentLoaded", function() {
    document.addEventListener("input", (e) => {
        if (e.target.id === "search") renderDevices();
    });
    renderDevices();
});