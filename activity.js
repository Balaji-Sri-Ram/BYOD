function applyFilters() {
    const filterType = document.getElementById('filterType').value;
    const activities = document.querySelectorAll('#activitiesContainer > div');
    let visibleCount = 0;
    let alertCount = 0;
    
    activities.forEach(activity => {
        const activityType = activity.getAttribute('data-type');
        
        if (filterType === 'all' || activityType === filterType) {
            activity.style.display = 'flex';
            visibleCount++;
            
            if (activityType === 'alert') {
                alertCount++;
            }
        } else {
            activity.style.display = 'none';
        }
    });
    
    document.getElementById('totalActivities').textContent = visibleCount;
    document.getElementById('activeAlerts').textContent = alertCount;
}


applyFilters();