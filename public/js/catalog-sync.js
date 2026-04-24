(() => {
    const isHome = window.location.pathname === '/' || window.location.pathname === '/index.php';
    if (!isHome) return;

    let reloadTimer = null;
    const channel = typeof BroadcastChannel !== 'undefined' ? new BroadcastChannel('beatdrop-catalog') : null;

    function scheduleReload() {
        if (reloadTimer) return;
        reloadTimer = window.setTimeout(() => {
            window.location.reload();
        }, 400);
    }

    window.addEventListener('storage', (event) => {
        if (event.key !== 'beatdrop_catalog_sync' || !event.newValue) return;
        scheduleReload();
    });

    channel?.addEventListener('message', () => {
        scheduleReload();
    });
})();
