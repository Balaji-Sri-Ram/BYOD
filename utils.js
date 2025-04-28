function addRecentActivity(message) {
    const timestamp = new Date().toISOString();
    const activity = { message, timestamp };
    let recentActivities = localStorage.getItem('recentActivities');
    recentActivities = recentActivities ? JSON.parse(recentActivities) : [];
    recentActivities.unshift(activity);
    localStorage.setItem('recentActivities', JSON.stringify(recentActivities));
    if (window.location.pathname.endsWith('index.html') || window.location.pathname === '/') {
        updateRecentActivitiesDisplay();
    }
}