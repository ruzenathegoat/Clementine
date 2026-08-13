/**
 * Lazy-load Echo + Pusher only when needed (concierge chat pages).
 * Call window.initEcho() before using window.Echo.
 */
window.initEcho = async function () {
    if (window.Echo) return; // already initialised

    const [{ default: Echo }, { default: Pusher }] = await Promise.all([
        import('laravel-echo'),
        import('pusher-js'),
    ]);

    window.Pusher = Pusher;

    window.Echo = new Echo({
        broadcaster: 'pusher',
        key: import.meta.env.VITE_PUSHER_APP_KEY,
        cluster: import.meta.env.VITE_PUSHER_APP_CLUSTER ?? 'mt1',
        forceTLS: true,
    });
};
