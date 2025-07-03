document.addEventListener('DOMContentLoaded', function () {
    const url = new URL(window.location.href);
    const wasConnected = url.searchParams.get('linkedin_connected') === '1';
    const token = url.searchParams.get('token');
    const profiles = url.searchParams.get('profiles');

    if (wasConnected && token && profiles) {
        const loadingIndicator = document.getElementById('linkedin-loading-indicator');
        if (loadingIndicator) {
            loadingIndicator.style.display = 'block';
        }
    }
});