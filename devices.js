document.addEventListener('DOMContentLoaded', () => {
    loadDevices();

    const searchInput = document.getElementById('search');
    searchInput.addEventListener('input', () => {
        filterDevices(searchInput.value);
    });
});

const deviceList = document.getElementById('device-list');
const registerModal = document.getElementById('register-modal');
const deviceNameInput = document.getElementById('device-name');
const ownerNameInput = document.getElementById('owner-name');
const macAddressInput = document.getElementById('mac-address');

let allDevices = []; // Store all fetched devices for filtering

function openModal() {
    registerModal.classList.remove('hidden');
}

function closeModal() {
    registerModal.classList.add('hidden');
    clearModalInputs();
}

function clearModalInputs() {
    deviceNameInput.value = '';
    ownerNameInput.value = '';
    macAddressInput.value = '';
}

async function loadDevices() {
    try {
        const response = await fetch('backend/get_devices.php');
        const data = await response.json();
        allDevices = data;
        renderDevices(data);
    } catch (error) {
        console.error('Error fetching devices:', error);
        deviceList.innerHTML = '<tr><td colspan="5" class="p-4 text-center">Failed to load devices.</td></tr>';
    }
}

function renderDevices(devices) {
    deviceList.innerHTML = '';
    if (devices.length === 0) {
        deviceList.innerHTML = '<tr><td colspan="5" class="p-4 text-center">No devices found.</td></tr>';
        return;
    }
    devices.forEach(device => {
        const row = deviceList.insertRow();
        row.className = "border-b hover:bg-gray-50";
        row.innerHTML = `
            <td class="p-2">${device.device_name}<br><small class="text-gray-500">${device.mac_address}</small></td>
            <td class="p-2">${device.owner_name}</td>
            <td class="p-2">
                <span class="status ${device.status}" onclick="updateDeviceStatus(${device.id}, '${device.status === 'active' ? 'inactive' : 'active'}')">
                    ${device.status.charAt(0).toUpperCase() + device.status.slice(1)}
                </span>
            </td>
            <td class="p-2">${device.last_active}</td>
            <td class="p-2"><button onclick="deleteDevice(${device.id})" class="text-red-600 hover:underline">Delete</button></td>
        `;
    });
    lucide.createIcons(); // Ensure icons are rendered if you're using them
}

async function addDevice() {
    const deviceName = deviceNameInput.value.trim();
    const ownerName = ownerNameInput.value.trim();
    const macAddress = macAddressInput.value.trim();

    if (!deviceName || !ownerName || !isValidMAC(macAddress)) {
        alert('Please fill in all fields with a valid MAC address.');
        return;
    }

    try {
        const response = await fetch('backend/add_device.php', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/x-www-form-urlencoded',
            },
            body: `device_name=${encodeURIComponent(deviceName)}&owner_name=${encodeURIComponent(ownerName)}&mac_address=${encodeURIComponent(macAddress)}`,
        });
        const data = await response.json();

        if (data.success) {
            closeModal();
            loadDevices(); // Reload the device list
        } else {
            alert('Error registering device: ' + data.message);
        }
    } catch (error) {
        console.error('Error registering device:', error);
        alert('An unexpected error occurred while registering the device.');
    }
}

async function updateDeviceStatus(deviceId, newStatus) {
    try {
        const response = await fetch('backend/update_status.php', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/x-www-form-urlencoded',
            },
            body: `device_id=${deviceId}&status=${newStatus}`,
        });
        const data = await response.json();

        if (data.success) {
            loadDevices(); // Reload the device list to update the UI
        } else {
            alert('Error updating status: ' + data.message);
        }
    } catch (error) {
        console.error('Error updating status:', error);
        alert('An unexpected error occurred while updating the status.');
    }
}

async function deleteDevice(deviceId) {
    if (confirm('Are you sure you want to delete this device?')) {
        try {
            const response = await fetch('backend/delete_device.php', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/x-www-form-urlencoded',
                },
                body: `device_id=${deviceId}`,
            });
            const data = await response.json();

            if (data.success) {
                loadDevices(); // Reload the device list
            } else {
                alert('Error deleting device: ' + data.message);
            }
        } catch (error) {
            console.error('Error deleting device:', error);
            alert('An unexpected error occurred while deleting the device.');
        }
    }
}

function filterDevices(searchTerm) {
    const lowerSearchTerm = searchTerm.toLowerCase();
    const filteredDevices = allDevices.filter(device =>
        device.device_name.toLowerCase().includes(lowerSearchTerm) ||
        device.owner_name.toLowerCase().includes(lowerSearchTerm) ||
        device.mac_address.toLowerCase().includes(lowerSearchTerm) ||
        device.status.toLowerCase().includes(lowerSearchTerm)
    );
    renderDevices(filteredDevices);
}

function isValidMAC(mac) {
    return /^([0-9A-Fa-f]{2}[:-]){5}([0-9A-Fa-f]{2})$/.test(mac);
}