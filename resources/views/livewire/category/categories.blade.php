<div class="min-h-screen bg-gray-50 dark:bg-neutral-900">
    {{-- Header --}}
    <div class="bg-primary-gradient shadow-lg">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-6">
            <div>
                <h1 class="text-3xl font-bold text-white">Categories</h1>
                <p class="text-green-100 mt-1">Organize your expenses with custom categories</p>
            </div>
        </div>
    </div>


    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-8">
        <div class="grid grid-cols-1 lg:grid-cols-6 gap-8">

            {{-- Form Card --}}
            <div class="lg:col-span-3">
                <div class="bg-white p-6 rounded-lg shadow-lg dark:bg-neutral-800">
                    <h2 class="text-xl font-semibold mb-4 text-gray-900 dark:text-white">
                        {{ $isEditing ? 'Edit Category' : 'Create Category' }}
                    </h2>

                    {{-- Flash Messages --}}


                    <form wire:submit.prevent="saveCategory" class="space-y-4">
                        {{-- Name --}}
                        <div>
                            <label class="block font-medium mb-1 text-gray-700 dark:text-gray-300">Category Name <span
                                    class="text-red-500">*</span></label>
                            <input type="text" wire:model.defer="name"
                                class="w-full border p-2 rounded focus:ring-2 focus:ring-blue-500 dark:bg-neutral-700 dark:border-neutral-600 dark:text-white"
                                placeholder="Enter category name" />
                            @error('name')
                                <span class="text-red-600 text-sm mt-1 block">{{ $message }}</span>
                            @enderror
                        </div>

                        {{-- Color --}}
                        <div>
                            <label class="block font-medium mb-1 text-gray-700 dark:text-gray-300">Category Color <span
                                    class="text-red-500">*</span></label>

                            {{-- Color Preview --}}
                            <div class="flex items-center space-x-3 mb-2">
                                <div class="w-8 h-8 rounded border"
                                    style="background-color: {{ $color }} !important" wire:ignore></div>
                                <span class="text-sm text-gray-600 dark:text-gray-400">Selected Color</span>
                            </div>

                            {{-- Preset Color Options --}}
                            <div class="grid grid-cols-5 md:grid-cols-10 gap-2 mb-3">
                                @foreach ($colors as $option)
                                    <button type="button" wire:click="$set('color', '{{ $option }}')"
                                        class="w-10 h-10 rounded border-2 transition-transform {{ $color === $option ? 'border-blue-500 scale-110 ring-2 ring-blue-200' : 'border-gray-300 hover:border-gray-400 hover:scale-105 dark:border-neutral-600 dark:hover:border-neutral-400' }}"
                                        style="background-color: {{ $option }} !important"
                                        title="{{ $option }}"></button>
                                @endforeach
                            </div>

                            {{-- Custom Color Picker --}}
                            <div class="flex items-center space-x-2">
                                <input type="color" wire:model.live="color" class="w-12 h-10 p-0 border rounded" />
                                <input type="text" wire:model.live="color"
                                    class="w-20 border p-1 rounded text-xs dark:bg-neutral-700 dark:border-neutral-600 dark:text-white"
                                    placeholder="#000000" maxlength="7" />
                            </div>

                            @error('color')
                                <span class="text-red-600 text-sm mt-1 block">{{ $message }}</span>
                            @enderror
                        </div>

                        {{-- Icon --}}
                        <div>
                            <label class="block font-medium mb-1 text-gray-700 dark:text-gray-300">Category Icon
                                (optional)</label>

                            {{-- ✨ ICON PREVIEW WITH SELECTED COLOR BACKGROUND --}}
                            @if ($icon)
                                <div
                                    class="flex items-center space-x-3 mb-3 p-3 bg-gray-50 rounded border dark:bg-neutral-700">
                                    <div class="w-10 h-10 rounded-lg flex items-center justify-center flex-shrink-0"
                                        style="background-color: {{ $color }} !important">
                                        <flux:icon :name="$icon" class="w-5 h-5 text-white" />
                                    </div>
                                    <div>
                                        <span
                                            class="text-sm font-medium text-gray-900 dark:text-white">{{ $icon }}</span>
                                        <p class="text-xs text-gray-500 dark:text-gray-400">Icon with selected color</p>
                                    </div>
                                    <button type="button" wire:click="$set('icon', '')"
                                        class="text-red-500 text-sm hover:text-red-700 dark:text-red-400 dark:hover:text-red-300">
                                        Remove
                                    </button>
                                </div>
                            @endif

                            {{-- Icon Search --}}
                            <div class="mb-3">
                                <input type="text" wire:model.live="iconSearch"
                                    class="w-full border p-2 rounded dark:bg-neutral-700 dark:border-neutral-600 dark:text-white"
                                    placeholder="Search icons..." />
                            </div>

                            {{-- Popular Icons Grid --}}
                            <div
                                class="grid grid-cols-6 md:grid-cols-8 gap-2 mb-3 max-h-48 overflow-y-auto border rounded p-2 dark:bg-neutral-700 dark:border-neutral-600">
                                @foreach ($filteredIcons as $iconName)
                                    <button type="button" wire:click="$set('icon', '{{ $iconName }}')"
                                        class="p-2 rounded border flex items-center justify-center hover:bg-gray-100 transition dark:hover:bg-neutral-600
                                        {{ $icon === $iconName ? 'bg-blue-100 border-blue-500 dark:bg-blue-900/20 dark:border-blue-500' : 'border-gray-200 dark:border-neutral-500' }}"
                                        title="{{ $iconName }}">
                                        <flux:icon :name="$iconName"
                                            class="w-5 h-5 {{ $icon === $iconName ? 'text-blue-600' : 'text-gray-700 dark:text-gray-300' }}" />
                                    </button>
                                @endforeach
                            </div>

                            {{-- Manual Input --}}
                            <input type="text" wire:model.defer="icon"
                                class="w-full border p-2 rounded dark:bg-neutral-700 dark:border-neutral-600 dark:text-white"
                                placeholder="Or enter custom heroicon name (e.g., home)" />
                        </div>

                        {{-- SIMPLIFIED SAVE BUTTON - NO SPINNER --}}
                        <button type="submit"
                            class="bg-primary-gradient text-white px-6 py-3 rounded hover:bg-blue-700 transition w-full flex items-center justify-center">
                            {{ $isEditing ? 'Update Category' : 'Save Category' }}
                        </button>
                        @if ($isEditing)
                            <button wire:click="cancelEdit" type="button"
                                class="bg-gray-600 text-white px-6 py-3 rounded hover:bg-gray-700 transition w-full flex items-center justify-center">
                                Cancel
                            </button>
                        @endif
                    </form>
                </div>
            </div>

            {{-- Categories List Card --}}
            <div class="lg:col-span-3">
                <div class="bg-white rounded-lg shadow-lg dark:bg-neutral-800">
                    <div class="px-6 py-4 border-b bg-gray-50 rounded-t-lg dark:bg-neutral-700 dark:border-neutral-600">
                        <h2 class="text-xl font-semibold text-gray-900 dark:text-white">Your Categories
                            ({{ $categories->count() }})</h2>
                    </div>

                    @if ($categories->count() > 0)
                        <div class="divide-y divide-gray-200 max-h-[500px] overflow-y-auto dark:divide-neutral-600">
                            @foreach ($categories as $category)
                                <div
                                    class="px-6 py-4 flex items-center justify-between hover:bg-gray-50 transition dark:hover:bg-neutral-700">
                                    <div class="flex items-center space-x-3">
                                        <div class="w-10 h-10 rounded-lg flex items-center justify-center text-white text-sm font-medium flex-shrink-0"
                                            style="background-color: {{ $category->color }} !important">
                                            @if ($category->icon)
                                                <flux:icon :name="$category->icon" class="w-5 h-5" />
                                            @else
                                                <flux:icon name="tag" class="w-5 h-5" />
                                            @endif
                                        </div>
                                        <div>
                                            <h3 class="font-medium text-gray-900 dark:text-white">{{ $category->name }}
                                            </h3>
                                            <p class="text-sm text-gray-500 dark:text-gray-400">
                                                {{ $category->expenses_count }}
                                                expense{{ $category->expenses_count !== 1 ? 's' : '' }}
                                            </p>
                                        </div>
                                    </div>

                                    <div class="flex items-center space-x-2">
                                        <button wire:click="editCategory({{ $category->id }})"
                                            class="text-blue-600 hover:text-blue-800 text-sm font-medium px-2 py-1 rounded hover:bg-blue-50 transition dark:text-blue-400 dark:hover:text-blue-300 dark:hover:bg-blue-900/20">
                                            <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24"
                                                stroke-width="1.5" stroke="currentColor" class="size-6">
                                                <path stroke-linecap="round" stroke-linejoin="round"
                                                    d="m16.862 4.487 1.687-1.688a1.875 1.875 0 1 1 2.652 2.652L10.582 16.07a4.5 4.5 0 0 1-1.897 1.13L6 18l.8-2.685a4.5 4.5 0 0 1 1.13-1.897l8.932-8.931Zm0 0L19.5 7.125M18 14v4.75A2.25 2.25 0 0 1 15.75 21H5.25A2.25 2.25 0 0 1 3 18.75V8.25A2.25 2.25 0 0 1 5.25 6H10" />
                                            </svg>

                                        </button>
                                        <button wire:click="deleteCategory({{ $category->id }})"
                                            wire:confirm="Are you sure you want to delete '{{ $category->name }}'?"
                                            class="text-red-600 hover:text-red-800 text-sm font-medium px-2 py-1 rounded hover:bg-red-50 transition dark:text-red-400 dark:hover:text-red-300 dark:hover:bg-red-900/20">
                                            <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24"
                                                stroke-width="1.5" stroke="currentColor" class="size-6">
                                                <path stroke-linecap="round" stroke-linejoin="round"
                                                    d="m14.74 9-.346 9m-4.788 0L9.26 9m9.968-3.21c.342.052.682.107 1.022.166m-1.022-.165L18.16 19.673a2.25 2.25 0 0 1-2.244 2.077H8.084a2.25 2.25 0 0 1-2.244-2.077L4.772 5.79m14.456 0a48.108 48.108 0 0 0-3.478-.397m-12 .562c.34-.059.68-.114 1.022-.165m0 0a48.11 48.11 0 0 1 3.478-.397m7.5 0v-.916c0-1.18-.91-2.164-2.09-2.201a51.964 51.964 0 0 0-3.32 0c-1.18.037-2.09 1.022-2.09 2.201v.916m7.5 0a48.667 48.667 0 0 0-7.5 0" />
                                            </svg>

                                        </button>
                                    </div>
                                </div>
                            @endforeach
                        </div>
                    @else
                        <div class="text-center py-12">
                            <div
                                class="w-16 h-16 bg-gray-100 rounded-full flex items-center justify-center mx-auto mb-4 dark:bg-neutral-700">
                                <flux:icon name="tag" class="w-8 h-8 text-gray-400 dark:text-gray-500" />
                            </div>
                            <h3 class="text-lg font-medium text-gray-900 mb-2 dark:text-white">No categories yet</h3>
                            <p class="text-gray-500 dark:text-gray-400">Create your first category using the form on
                                the left!</p>
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>

</div>
