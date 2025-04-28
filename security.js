function updatePolicies() {
    alert('Security policies have been updated!');
    addRecentActivity('Security policies updated');
}

function runSecurityScan() {
    alert('Running security scan...');
    addRecentActivity('Security scan initiated');
}

function addRecentActivity(message) {
    let activities = localStorage.getItem('recentActivities');
    activities = activities ? JSON.parse(activities) : [];
    activities.unshift({ message: message, timestamp: new Date().toISOString() });
    localStorage.setItem('recentActivities', JSON.stringify(activities.slice(0, 5)));

    if (typeof updateRecentActivitiesDisplay === 'function') {
        updateRecentActivitiesDisplay();
    }
}
