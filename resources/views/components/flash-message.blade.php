@props(['position' => 'top-right'])

<div id="toast-container"
     @class([
        'fixed z-[9999] w-full max-w-[90vw] sm:max-w-[400px] space-y-2 p-2 sm:p-3',
        'top-4 left-4' => $position === 'top-left',
        'top-4 right-4' => $position === 'top-right',
        'bottom-4 left-4' => $position === 'bottom-left',
        'bottom-4 right-4' => $position === 'bottom-right',
        'top-1/2 left-1/2 -translate-x-1/2 -translate-y-1/2' => $position === 'center',
     ])>
</div>

<script>
    console.log('[Toast] Toast container initialized');

    window.addEventListener('show-flash-message', function(e) {
        console.log('[Toast] Event received:', e.detail);
        const { type, message } = e.detail;
        showToast(type, message);
    });

    // SESSION FLASH LOGS
    @if (session('success'))
        console.log('[Toast] Session success detected:', '{{ addslashes(session('success')) }}');
        $(document).ready(function () {
            showToast('success', '{{ addslashes(session('success')) }}');
        });
    @endif

    @if (session('error'))
        console.log('[Toast] Session error detected:', '{{ addslashes(session('error')) }}');
        $(document).ready(function () {
            showToast('error', '{{ addslashes(session('error')) }}');
        });
    @endif

    @if (session('warning'))
        console.log('[Toast] Session warning detected:', '{{ addslashes(session('warning')) }}');
        $(document).ready(function () {
            showToast('warning', '{{ addslashes(session('warning')) }}');
        });
    @endif

    @if (session('info'))
        console.log('[Toast] Session info detected:', '{{ addslashes(session('info')) }}');
        $(document).ready(function () {
            showToast('info', '{{ addslashes(session('info')) }}');
        });
    @endif
</script>