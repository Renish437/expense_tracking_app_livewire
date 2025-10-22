@props(['name' => 'folder', 'class' => 'w-6 h-6', 'solid' => false])

@php
    $iconDir = $solid ? 'solid' : 'outline';
@endphp

{{-- ✅ DIRECT ASSET LOADING - NO PARSING! --}}
<img 
    src="{{ asset("heroicons/{$iconDir}/{$name}.svg") }}" 
    class="{{ $class }} filter invert brightness-0"
    alt="{{ $name }}"
    
    onerror="this.style.display='none'; this.nextElementSibling.style.display='block'"
/>

{{-- ✅ HIDDEN FALLBACK --}}
<div class="w-full h-full flex items-center justify-center {{ $class }} hidden">
    <svg viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
        <path d="M3 7V5a2 2 0 012-2h13a1 1 0 011 1v2m0 0h3v12a2 2 0 01-2 2H5a2 2 0 01-2-2V7m18 0H3" 
              stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/>
    </svg>
</div>