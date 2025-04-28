function openModal() {
    document.getElementById("register-modal").classList.remove("hidden");
}

function closeModal() {
    document.getElementById("register-modal").classList.add("hidden");
    document.getElementById("device-name").value = "";
    document.getElementById("owner-name").value = "";
    document.getElementById("mac-address").value = "";
}

async function updateDashboardDeviceCount() {
    try {
        const response = await fetch("get_device_count.php");
        const result = await response.json();
        if (result.success) {
            const registeredDevicesElement = document.querySelectorAll('#main-content .text-4xl.font-bold')[1];
            if (registeredDevicesElement) {
                registeredDevicesElement.innerText = result.count;
            }
        } else {
            console.error("Failed to fetch device count for dashboard:", result.message);
        }
    } catch (error) {
        console.error("Error fetching device count for dashboard:", error);
    }
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
            await renderDevices();
            logDeviceRegistrationActivity(name, owner);
            if (window.location.pathname.endsWith('index.html') || window.location.pathname === '/') {
                await updateDashboardDeviceCount();
                if (typeof updateRecentActivitiesDisplay === 'function') {
                    updateRecentActivitiesDisplay();
                }
            }
        } else {
            alert("Failed to register device: " + (result.message || "Unknown error"));
        }
    } catch (error) {
        console.error("Error while adding device:", error);
        alert("Error occurred while registering the device.");
    }
}

async function deleteDevice(macAddress) {
    if (confirm(`Are you sure you want to delete the device with MAC address: ${macAddress}?`)) {
        try {
            const response = await fetch("delete_device.php", { 
                method: "POST",
                headers: {
                    "Content-Type": "application/json"
                },
                body: JSON.stringify({ mac: macAddress })
            });

            const result = await response.json();

            if (result.success) {
                alert("Device deleted successfully!");
                await renderDevices(); 

                const deletedDevice = document.querySelector(`#device-list tr:has(button[onclick*="'${macAddress}'"]) td:first-child`)?.textContent;
                if (deletedDevice) {
                    addRecentActivity(`Device "${deletedDevice.trim()}" deleted`);
                } else {
                    addRecentActivity(`Device with MAC address "${macAddress}" deleted`);
                }

                if (window.location.pathname.endsWith('index.html') || window.location.pathname === '/') {
                    await updateDashboardDeviceCount();
                    if (typeof updateDashboardData === 'function') {
                        updateDashboardData();
                    }
                }
            } else {
                alert("Failed to delete device: " + (result.message || "Unknown error"));
            }
        } catch (error) {
            console.error("Error while deleting device:", error);
            alert("Error occurred while deleting the device.");
        }
    }
}

function logDeviceRegistrationActivity(deviceName, ownerName) {
    const message = `New device "${deviceName}" registered by ${ownerName}.`;
    addRecentActivity(message);
}

async function toggleStatus(mac, el) {
    const newStatus = el.classList.contains("active") ? "inactive" : "active";
    el.textContent = newStatus.charAt(0).toUpperCase() + newStatus.slice(1);
    el.className = `status ${newStatus}`;

    try {
        const res = await fetch("update_status.php", {
            method: "POST",
            headers: { "Content-Type": "application/json" },
            body: JSON.stringify({ mac, status: newStatus })
        });

        const result = await res.json();
        if (!result.success) {
            const previousStatus = newStatus === "active" ? "inactive" : "active";
            el.textContent = previousStatus.charAt(0).toUpperCase() + previousStatus.slice(1);
            el.className = `status ${previousStatus}`;
            alert(result.message || "Status update failed.");
        } else if (window.parent && window.parent.document.getElementById('main-content').querySelector('#device-status-list')) {
            updateDashboardDeviceStatus(mac, newStatus);
        }
    } catch (err) {
        const previousStatus = newStatus === "active" ? "inactive" : "active";
        el.textContent = previousStatus.charAt(0).toUpperCase() + previousStatus.slice(1);
        el.className = `status ${previousStatus}`;
        console.error("Status toggle failed:", err);
    }
}


async function renderDevices() {
    try {
        const searchQuery = document.getElementById("search").value.toLowerCase();
        const response = await fetch("get_device_statuses.php");
        const result = await response.json();
        const deviceList = document.getElementById("device-list");

        if (result.success && deviceList) {
            deviceList.innerHTML = "";
            result.devices
                .filter(device =>
                    device.device_name.toLowerCase().includes(searchQuery) ||
                    device.owner_name.toLowerCase().includes(searchQuery) ||
                    device.mac_address.toLowerCase().includes(searchQuery)
                )
                .forEach(device => {
                    const row = document.createElement("tr");
                    row.className = "border-b";
                    row.innerHTML = `
                        <td class="p-3">
                            <p class="font-semibold text-black">${device.device_name}</p>
                            <p class="text-gray-500 text-xs">${device.mac_address}</p>
                        </td>
                        <td class="p-3">${device.owner_name}</td>
                        <td class="p-3">
                            <span class="status ${device.status}" onclick="toggleStatus('${device.mac_address}', this)">
                                ${device.status.charAt(0).toUpperCase() + device.status.slice(1)}
                            </span>
                        </td>
                        <td class="p-3">${device.last_active || "N/A"}</td>
                        <td class="p-3">
                            <button onclick="deleteDevice('${device.mac_address}')" class="text-red-600 hover:underline">Delete</button>
                        </td>
                    `;
                    deviceList.appendChild(row);
                });
        } else {
            deviceList.innerHTML = '<tr><td colspan="5" class="p-2 text-gray-500">No devices found.</td></tr>';
        }
    } catch (error) {
        console.error("Error rendering devices:", error);
        document.getElementById("device-list").innerHTML = '<tr><td colspan="5" class="p-2 text-gray-500">Error loading devices.</td></tr>';
    }
}


function updateDashboardDeviceStatus(mac, status) {
    const deviceDiv = document.querySelector(`#device-status-list [data-mac="${mac}"]`);
    if (deviceDiv) {
        const statusSpan = deviceDiv.querySelector("span");
        statusSpan.textContent = status.charAt(0).toUpperCase() + status.slice(1);
        statusSpan.className = `text-xs font-semibold inline-flex items-center px-3 py-1 rounded-full ${status === "active" ? "bg-green-100 text-green-700" : "bg-red-100 text-red-700"}`;
    }
}