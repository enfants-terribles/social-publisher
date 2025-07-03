document.addEventListener('DOMContentLoaded', function () {
    const loadingIndicator = document.getElementById('linkedin-loading-indicator');
    const isConnected = window.location.href.includes('linkedin_connected=1');

    if (!isConnected || !loadingIndicator) return;

    const observer = new MutationObserver(() => {
        const profileDropdownWrapper = document.getElementById('linkedin-profile-dropdown-wrapper');
        const profileSelect = document.getElementById('linkedin-profile-select');

        if (profileDropdownWrapper && profileSelect) {
            loadingIndicator.style.display = 'none';
            profileDropdownWrapper.style.display = 'block';
            profileSelect.removeAttribute('hidden');

            // Clean URL
            const url = new URL(window.location.href);
            ['linkedin_connected', 'token', 'profiles', '_wpnonce'].forEach(param => url.searchParams.delete(param));
            window.history.replaceState({}, document.title, url.pathname + url.search);

            observer.disconnect(); // stop observing
        }
    });

    observer.observe(document.body, { childList: true, subtree: true });

    setTimeout(function () {
        const url = new URL(window.location.href);
        url.searchParams.delete("linkedin_connected");
        url.searchParams.delete("token");
        url.searchParams.delete("profiles");
        url.searchParams.delete("_wpnonce");
        window.history.replaceState({}, document.title, url.pathname + url.search);
        location.reload();
    }, 1500);
});