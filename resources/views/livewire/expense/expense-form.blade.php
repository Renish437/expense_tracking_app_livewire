<div class="min-h-screen">
    <!-- Header -->
    <div class="bg-primary-gradient shadow-lg border-b border-neutral-300 dark:border-neutral-900">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-6">
            <div class="flex items-center justify-between">
                <div>
                    <h1 class="text-3xl font-bold text-white">
                        {{ $isEdit ? 'Edit Expense' : 'Add New Expense' }}
                    </h1>
                    <p class="text-purple-100 mt-1">
                        {{ $isEdit ? 'Update expense details' : 'Record a new expense' }}
                    </p>
                </div>
                <div class="flex items-center gap-4">
                    <!-- Dark Mode Toggle -->

                    <a href="/expenses" class="text-white hover:text-neutral-200 transition cursor-pointer">
                        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M6 18L18 6M6 6l12 12" />
                        </svg>
                    </a>
                </div>
            </div>
        </div>
    </div>

    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 dark:bg-neutral-900 py-8">
        <form wire:submit="save" class="space-y-8">
            <!-- Basic Information Card -->
            <div
                class="bg-white dark:bg-neutral-800 rounded-2xl shadow-xl p-8 border border-neutral-200 dark:border-neutral-700">
                <h3 class="text-xl font-bold text-neutral-900 dark:text-white mb-6">Basic Information</h3>
                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <!-- Amount -->
                    <div>
                        <label for="amount"
                            class="block text-sm font-semibold text-neutral-700 dark:text-neutral-300 mb-2">
                            Amount <span class="text-red-500">*</span>
                        </label>
                        <div class="relative">
                            <span
                                class="absolute inset-y-0 left-0 pl-3 flex items-center text-neutral-500 dark:text-neutral-400 text-lg">$</span>
                            <input type="number" id="amount" wire:model="amount" step="0.01" min="0"
                                placeholder="0.00"
                                class="w-full pl-10 pr-4 py-3 bg-white dark:bg-neutral-700 border border-neutral-300 dark:border-neutral-600 rounded-xl focus:ring-2 focus:ring-purple-500 focus:border-transparent text-neutral-900 dark:text-white @error('amount') border-red-500 @enderror">
                        </div>
                        @error('amount')
                            <p class="mt-1 text-sm text-red-500">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- Date -->
                    <div>
                        <label for="date"
                            class="block text-sm font-semibold text-neutral-700 dark:text-neutral-300 mb-2">
                            Date <span class="text-red-500">*</span>
                        </label>
                        <input type="date" id="date" wire:model="date"
                            class="w-full px-4 py-3 bg-white dark:bg-neutral-700 border border-neutral-300 dark:border-neutral-600 rounded-xl focus:ring-2 focus:ring-purple-500 focus:border-transparent text-neutral-900 dark:text-white @error('date') border-red-500 @enderror">
                        @error('date')
                            <p class="mt-1 text-sm text-red-500">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- Title -->
                    <div class="md:col-span-2">
                        <label for="title"
                            class="block text-sm font-semibold text-neutral-700 dark:text-neutral-300 mb-2">
                            Title <span class="text-red-500">*</span>
                        </label>
                        <input type="text" id="title" wire:model="title" placeholder="e.g., Grocery Shopping"
                            class="w-full px-4 py-3 bg-white dark:bg-neutral-700 border border-neutral-300 dark:border-neutral-600 rounded-xl focus:ring-2 focus:ring-purple-500 focus:border-transparent text-neutral-900 dark:text-white @error('title') border-red-500 @enderror">
                        @error('title')
                            <p class="mt-1 text-sm text-red-500">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- Category -->
                    <div class="md:col-span-2">
                        <label for="category_id"
                            class="block text-sm font-semibold text-neutral-700 dark:text-neutral-300 mb-2">
                            Category
                        </label>
                        <select wire:model="category_id" id="category_id"
                            class="w-full px-4 py-3 bg-white dark:bg-neutral-700 border border-neutral-300 dark:border-neutral-600 rounded-xl focus:ring-2 focus:ring-purple-500 focus:border-transparent text-neutral-900 dark:text-white">
                            <option value="">Select a category</option>
                            @foreach ($categories as $category)
                                <option value="{{ $category->id }}">{{ $category->name }}</option>
                            @endforeach
                        </select>
                        <p class="mt-2 text-sm text-neutral-500 dark:text-neutral-400">
                            Don't see your category? <a href="/categories"
                                class="text-purple-500 dark:text-purple-400 font-medium hover:underline cursor-pointer">Create
                                one</a>
                        </p>
                    </div>

                    <!-- Description -->
                    <div class="md:col-span-2">
                        <label for="description"
                            class="block text-sm font-semibold text-neutral-700 dark:text-neutral-300 mb-2">
                            Description
                        </label>
                        <textarea wire:model="description" id="description" rows="3" placeholder="Add any additional notes..."
                            class="w-full px-4 py-3 bg-white dark:bg-neutral-700 border border-neutral-300 dark:border-neutral-600 rounded-xl focus:ring-2 focus:ring-purple-500 focus:border-transparent text-neutral-900 dark:text-white resize-none"></textarea>
                    </div>
                </div>
            </div>

            <!-- Expense Type Card -->
            <div
                class="bg-white dark:bg-neutral-800 rounded-2xl shadow-xl p-8 border border-neutral-200 dark:border-neutral-700">
                <h3 class="text-xl font-bold text-neutral-900 dark:text-white mb-6">Expense Type</h3>
                <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mb-6">
                    <!-- One-time -->
                    <label
                        class="relative flex items-center p-5 border-2 rounded-xl cursor-pointer transition
                        {{ $type === 'one-time'
                            ? 'border-purple-500 bg-purple-50 dark:bg-purple-900/20'
                            : 'border-neutral-300 dark:border-neutral-600 hover:border-purple-400 dark:hover:border-purple-500' }}">
                        <input type="radio" wire:model.live="type" value="one-time" class="sr-only">
                        <div class="flex-1 flex items-center gap-4">
                            <div
                                class="w-12 h-12 rounded-lg flex items-center justify-center
                                {{ $type === 'one-time' ? 'bg-purple-600' : 'bg-neutral-300 dark:bg-neutral-600' }}">
                                <svg class="w-7 h-7 text-white" fill="none" stroke="currentColor"
                                    viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" />
                                </svg>
                            </div>
                            <div>
                                <div class="font-bold text-neutral-900 dark:text-white">One-time</div>
                                <div class="text-sm text-neutral-600 dark:text-neutral-400">Single expense</div>
                            </div>
                        </div>
                        @if ($type === 'one-time')
                            <svg class="w-6 h-6 text-purple-600" fill="currentColor" viewBox="0 0 20 20">
                                <path fill-rule="evenodd"
                                    d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z"
                                    clip-rule="evenodd" />
                            </svg>
                        @endif
                    </label>

                    <!-- Recurring -->
                    <label
                        class="relative flex items-center p-5 border-2 rounded-xl cursor-pointer transition
                        {{ $type === 'recurring'
                            ? 'border-purple-500 bg-purple-50 dark:bg-purple-900/20'
                            : 'border-neutral-300 dark:border-neutral-600 hover:border-purple-400 dark:hover:border-purple-500' }}">
                        <input type="radio" wire:model.live="type" value="recurring" class="sr-only">
                        <div class="flex-1 flex items-center gap-4">
                            <div
                                class="w-12 h-12 rounded-lg flex items-center justify-center
                                {{ $type === 'recurring' ? 'bg-purple-600' : 'bg-neutral-300 dark:bg-neutral-600' }}">
                                <svg class="w-7 h-7 text-white" fill="none" stroke="currentColor"
                                    viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15" />
                                </svg>
                            </div>
                            <div>
                                <div class="font-bold text-neutral-900 dark:text-white">Recurring</div>
                                <div class="text-sm text-neutral-600 dark:text-neutral-400">Repeating expense</div>
                            </div>
                        </div>
                        @if ($type === 'recurring')
                            <svg class="w-6 h-6 text-purple-600" fill="currentColor" viewBox="0 0 20 20">
                                <path fill-rule="evenodd"
                                    d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z"
                                    clip-rule="evenodd" />
                            </svg>
                        @endif
                    </label>
                </div>

                @if ($type === 'recurring')
                    <div
                        class="p-6 bg-purple-50 dark:bg-purple-900/30 rounded-xl border-2 border-purple-200 dark:border-purple-700">
                        <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
                            <!-- Frequency -->
                            <div>
                                <label class="block text-sm font-semibold text-purple-900 dark:text-purple-200 mb-2">
                                    Frequency <span class="text-red-500">*</span>
                                </label>
                                <select wire:model="recurring_frequency"
                                    class="w-full px-4 py-3 bg-white dark:bg-neutral-800 border border-purple-300 dark:border-purple-600 rounded-xl focus:ring-2 focus:ring-purple-500 text-neutral-900 dark:text-white">
                                    <option value="daily">Daily</option>
                                    <option value="weekly">Weekly</option>
                                    <option value="monthly">Monthly</option>
                                    <option value="yearly">Yearly</option>
                                </select>
                                @error('recurring_frequency')
                                    <p class="mt-1 text-sm text-red-500">{{ $message }}</p>
                                @enderror
                            </div>
                            <!-- Start Date -->
                            <div>
                                <label class="block text-sm font-semibold text-purple-900 dark:text-purple-200 mb-2">
                                    Start Date <span class="text-red-500">*</span>
                                </label>
                                <input type="date" wire:model="recurring_start_date"
                                    class="w-full px-4 py-3 bg-white dark:bg-neutral-800 border border-purple-300 dark:border-purple-600 rounded-xl focus:ring-2 focus:ring-purple-500 text-neutral-900 dark:text-white">
                                @error('recurring_start_date')
                                    <p class="mt-1 text-sm text-red-500">{{ $message }}</p>
                                @enderror
                            </div>
                            <!-- End Date -->
                            <div>
                                <label class="block text-sm font-semibold text-purple-900 dark:text-purple-200 mb-2">
                                    End Date <span class="text-neutral-500 dark:text-neutral-400">(Optional)</span>
                                </label>
                                <input type="date" wire:model="recurring_end_date"
                                    class="w-full px-4 py-3 bg-white dark:bg-neutral-800 border border-purple-300 dark:border-purple-600 rounded-xl focus:ring-2 focus:ring-purple-500 text-neutral-900 dark:text-white">
                                @error('recurring_end_date')
                                    <p class="mt-1 text-sm text-red-500">{{ $message }}</p>
                                @enderror
                            </div>
                        </div>
                        <div class="mt-4 flex items-start gap-3 text-sm text-purple-800 dark:text-purple-300">
                            <svg class="w-5 h-5 mt-0.5 flex-shrink-0" fill="currentColor" viewBox="0 0 20 20">
                                <path fill-rule="evenodd"
                                    d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7-4a1 1 0 11-2 0 1 1 0 012 0zM9 9a1 1 0 000 2v3a1 1 0 001 1h1a1 1 0 100-2v-3a1 1 0 00-1-1H9z"
                                    clip-rule="evenodd" />
                            </svg>
                            <div>
                                <strong>Note:</strong> This expense will auto-generate entries based on your frequency.
                                Scheduler runs nightly.
                            </div>
                        </div>
                    </div>
                @endif
            </div>

            <!-- Form Actions -->
            <div class="flex flex-col sm:flex-row gap-4 justify-between pt-6">
                <a href="/expenses"
                    class="px-8 py-3 border border-neutral-300 dark:border-neutral-600 cursor-pointer rounded-xl text-neutral-700 dark:text-neutral-300 font-semibold hover:bg-neutral-100 dark:hover:bg-neutral-700 transition">
                    Cancel
                </a>
                <button type="submit"
                    class="px-10 py-3 bg-primary-gradient cursor-pointer hover:from-purple-700 hover:to-indigo-700 text-white font-bold rounded-xl shadow-lg hover:shadow-xl transform hover:-translate-y-0.5 transition flex items-center gap-2">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7" />
                    </svg>
                    {{ $isEdit ? 'Update Expense' : 'Save Expense' }}
                </button>
            </div>
        </form>
    </div>
</div>
