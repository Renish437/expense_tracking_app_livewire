const showToast = (type, message) => {
    console.log('[Toast] showToast called:', { type, message });

    const toastId = `toast-${Date.now()}`;
    console.log('[Toast] Generated ID:', toastId);

    const typeToClassMap = {
        success: 'bg-green-100 border-l-4 border-green-500 text-green-700',
        error: 'bg-red-100 border-l-4 border-red-500 text-red-700',
        warning: 'bg-yellow-100 border-l-4 border-yellow-500 text-yellow-700',
        info: 'bg-blue-100 border-l-4 border-blue-500 text-blue-700'
    };

    const icons = {
        success: `<svg class="w-5 h-5 sm:w-6 sm:h-6 mr-2 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path></svg>`,
        error: `<svg class="w-5 h-5 sm:w-6 sm:h-6 mr-2 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path></svg>`,
        warning: `<svg class="w-5 h-5 sm:w-6 sm:h-6 mr-2 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"></path></svg>`,
        info: `<svg class="w-5 h-5 sm:w-6 sm:h-6 mr-2 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>`
    };

    const validType = typeToClassMap[type] ? type : 'info';
    const validIcon = icons[type] ? icons[type] : icons.info;

    console.log('[Toast] Validated type:', validType);

    const toastHtml = `
        <div id="${toastId}" 
             class="p-3 sm:p-4 rounded-lg shadow-lg flex items-center justify-between w-full max-w-full opacity-0 scale-95 transform transition-all duration-300 ease-out ${typeToClassMap[validType]}">
            <div class="flex items-center flex-1 pr-4">
                ${validIcon}
                <p class="text-sm sm:text-base leading-tight">${message}</p>
            </div>
            <button class="toast-close-btn text-gray-500 hover:text-gray-700 flex-shrink-0">
                <svg class="w-4 h-4 sm:w-5 sm:h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                </svg>
            </button>
        </div>
    `;

    console.log('[Toast] HTML generated, appending to container...');

    const $toast = $(toastHtml).appendTo('#toast-container');
    console.log('[Toast] Toast appended to DOM:', $toast[0]);

    setTimeout(() => {
        $toast.removeClass('opacity-0 scale-95');
        console.log('[Toast] Animation IN started');
    }, 10);

    const removeToast = () => {
        $toast.addClass('opacity-0 scale-95');
        console.log('[Toast] Animation OUT started');
        $toast.on('transitionend', function() {
            $(this).remove();
            console.log('[Toast] Toast removed from DOM');
        });
    };

    const timer = setTimeout(removeToast, 4000);
    console.log('[Toast] Auto-remove timer set (4s)');

    $toast.find('.toast-close-btn').on('click', function() {
        clearTimeout(timer);
        console.log('[Toast] Close button clicked - removing immediately');
        removeToast();
    });
};