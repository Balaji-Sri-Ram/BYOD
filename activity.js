function applyFilters() {
    const filterType = document.getElementById('filterType').value;
    const activities = document.querySelectorAll('#activitiesContainer > div');
    let visibleCount = 0;
    let alertCount = 0;

    activities.forEach(activity => {
        const activityType = activity.getAttribute('data-type');

        if (filterType === 'all' || activityType === filterType) {
            activity.classList.remove('hidden');
            visibleCount++;

            if (activityType === 'alert') {
                alertCount++;
            }
        } else {
            activity.classList.add('hidden');
        }
    });

    document.getElementById('totalActivities').textContent = visibleCount;
    document.getElementById('activeAlerts').textContent = alertCount;
}

document.addEventListener('DOMContentLoaded', applyFilters);


