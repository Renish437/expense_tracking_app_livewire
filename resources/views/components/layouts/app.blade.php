<x-layouts.app.sidebar :title="$title ?? null">

    <flux:main>
         <x-flash-message position="top-right" />
        {{ $slot }}
    </flux:main>


</x-layouts.app.sidebar>
