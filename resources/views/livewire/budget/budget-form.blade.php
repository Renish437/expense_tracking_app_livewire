<div class="min-h-screen bg-neutral-50 dark:bg-neutral-900 rounded">
    <!-- Header -->
    <div class="bg-gradient-to-r from-indigo-600 to-purple-600 shadow-lg">
        <div class="max-w-6xl mx-auto px-4 sm:px-6 lg:px-8 py-6">
            <div class="flex items-center justify-between">
                <div>
                    <h1 class="text-3xl font-bold text-white">
                        {{ $isEdit ? 'Edit Budget' : 'Create New Budget' }}
                    </h1>
                    <p class="text-indigo-100 mt-1">
                        {{ $isEdit ? 'Update your budget details' : 'Set spending limits for better financial control' }}
                    </p>
                </div>
                <a href="{{ route('budget.index') }}" class="text-indigo-100 hover:text-white transition">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/>
                    </svg>
                </a>
            </div>
        </div>
    </div>
    <div class="max-w-6xl mx-auto px-4 sm:px-6 lg:px-8 py-6">
        <form wire:submit="save" class="w-full space-y-6">
            <!-- Session Message -->
            @if (session()->has('message'))
                <div class="mb-6 bg-green-100 dark:bg-green-900/50 border border-green-200 dark:border-green-700 text-green-800 dark:text-green-200 px-4 py-3 rounded-lg">
                    {{ session('message') }}
                </div>
            @endif
            <!-- Budget Period Card -->
            <div class="bg-white dark:bg-neutral-800 rounded-xl shadow-md p-6">
                <h3 class="text-lg font-semibold text-neutral-800 dark:text-neutral-200 mb-6 flex items-center gap-2">
                    <svg class="w-6 h-6 text-indigo-600 dark:text-indigo-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"/>
                    </svg>
                    Budget Period
                </h3>
                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <!-- Month -->
                    <div>
                        <label for="month" class="block text-sm font-medium text-neutral-700 dark:text-neutral-300 mb-2">
                            Month <span class="text-red-500">*</span>
                        </label>
                        <select wire:model.live="month" id="month" class="w-full px-4 py-3 border dark:bg-neutral-700 border-neutral-300 dark:border-neutral-600 rounded-lg focus:ring-2 focus:ring-indigo-500 focus:border-transparent @error('month') border-red-500 dark:border-red-400 @enderror">
                            <option value="">Select Month</option>
                            @foreach($months as $monthOption)
                                <option value="{{ $monthOption['value'] }}" {{ $month == $monthOption['value'] ? 'selected' : '' }}>
                                    {{ $monthOption['name'] }} 
                                </option>
                            @endforeach
                        </select>
                        @error('month')
                            <p class="mt-1 text-sm text-red-600 dark:text-red-400">{{ $message }}</p>
                        @enderror
                    </div>
                    <!-- Year -->
                    <div>
                        <label for="year" class="block text-sm font-medium text-neutral-700 dark:text-neutral-300 mb-2">
                            Year <span class="text-red-500">*</span>
                        </label>
                        <select wire:model.live="year" id="year" class="w-full px-4 py-3 border dark:bg-neutral-700 border-neutral-300 dark:border-neutral-600 rounded-lg focus:ring-2 focus:ring-indigo-500 focus:border-transparent @error('year') border-red-500 dark:border-red-400 @enderror">
                            <option value="">Select Year</option>
                            @foreach($years as $yearOption)
                                <option value="{{ $yearOption }}" {{ $year == $yearOption ? 'selected' : '' }}>
                                    {{ $yearOption }}
                                </option>
                            @endforeach
                        </select>
                        @error('year')
                            <p class="mt-1 text-sm text-red-600 dark:text-red-400">{{ $message }}</p>
                        @enderror
                    </div>
                </div>
                <!-- Combined Date Message -->
                {{-- @if($monthFromQuery && $yearFromQuery)
                    <p class="mt-2 text-sm text-indigo-600 dark:text-indigo-400">
                        Date pre-selected from URL: {{ \Carbon\Carbon::create($year, $month, 1)->format('F Y') }}
                    </p>
                @else
                    @if(request()->query('month') && !$monthFromQuery)
                        <p class="mt-2 text-sm text-yellow-600 dark:text-yellow-400">
                            Invalid month provided in URL ({{ request()->query('month') }}). Defaulting to current month.
                        </p>
                    @endif
                    @if(request()->query('year') && !$yearFromQuery)
                        <p class="mt-2 text-sm text-yellow-600 dark:text-yellow-400">
                            Invalid year provided in URL ({{ request()->query('year') }}). Defaulting to current year.
                        </p>
                    @endif
                @endif --}}
            </div>
            <!-- Budget Details Card -->
            <div class="bg-white dark:bg-neutral-800 rounded-xl shadow-md p-6">
                <h3 class="text-lg font-semibold text-neutral-800 dark:text-neutral-200 mb-6 flex items-center gap-2">
                    <svg class="w-6 h-6 text-indigo-600 dark:text-indigo-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
                    </svg>
                    Budget Details
                </h3>
                <div class="space-y-6">
                    <!-- Category -->
                    <div>
                        <label for="category_id" class="block text-sm font-medium text-neutral-700 dark:text-neutral-200 mb-2">
                            Category
                        </label>
                        <select wire:model.live="category_id" id="category_id" class="w-full px-4 py-3 border dark:bg-neutral-700 border-neutral-300 dark:border-neutral-600 rounded-lg focus:ring-2 focus:ring-indigo-500 focus:border-transparent @error('category_id') border-red-500 dark:border-red-400 @enderror">
                            <option value="">Overall Budget (All Categories)</option>
                            @foreach($categories as $category)
                                <option value="{{ $category->id }}" {{ $category_id == $category->id ? 'selected' : '' }}>
                                    {{ $category->name }}
                                </option>
                            @endforeach
                        </select>
                        @error('category_id')
                            <p class="mt-1 text-sm text-red-600 dark:text-red-400">{{ $message }}</p>
                        @enderror
                        <p class="mt-2 text-sm text-neutral-600 dark:text-neutral-400">
                            Leave blank to create an overall budget, or select a category for specific tracking.
                        </p>
                    </div>
                    <!-- Amount -->
                    <div>
                        <label for="amount" class="block text-sm font-medium text-neutral-700 dark:text-neutral-200 mb-2">
                            Budget Amount <span class="text-red-500">*</span>
                        </label>
                        <div class="relative">
                            <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                                <span class="text-neutral-500 dark:text-neutral-400 text-xl">$</span>
                            </div>
                            <input type="number" id="amount" wire:model.live="amount" step="0.01" min="0" placeholder="0.00" class="w-full pl-10 pr-4 py-3 border border-neutral-300 dark:border-neutral-600 rounded-lg focus:ring-2 focus:ring-indigo-500 focus:border-transparent text-lg @error('amount') border-red-500 dark:border-red-400 @enderror">
                        </div>
                        @error('amount')
                            <p class="mt-1 text-sm text-red-600 dark:text-red-400">{{ $message }}</p>
                        @enderror
                    </div>
                    <!-- Preview Card -->
                    @if($amount && $month && $year)
                        <div class="p-4 bg-gradient-to-r from-indigo-50 to-purple-50 dark:from-indigo-900/50 dark:to-purple-900/50 rounded-lg border-2 border-indigo-200 dark:border-indigo-700">
                            <p class="text-sm font-medium text-indigo-900 dark:text-indigo-200 mb-2">Budget Preview:</p>
                            <div class="flex items-center justify-between">
                                <div>
                                    <p class="text-2xl font-bold text-indigo-900 dark:text-indigo-200">${{ number_format($amount, 2) }}</p>
                                    <p class="text-sm text-indigo-700 dark:text-indigo-300">
                                        {{ $category_id ? ($categories->find($category_id) ? $categories->find($category_id)->name : 'Overall Budget') : 'Overall Budget' }}
                                    </p>
                                </div>
                                <div class="text-right">
                                    <p class="text-sm text-indigo-700 dark:text-indigo-300">
                                        {{ \Carbon\Carbon::create($year, $month, 1)->format('F Y') }}
                                    </p>
                                    <p class="text-xs text-indigo-600 dark:text-indigo-400 mt-1">
                                        ≈ ${{ number_format($amount / 30, 2) }}/day
                                    </p>
                                </div>
                            </div>
                        </div>
                    @endif
                </div>
            </div>
            <!-- Tips Card -->
            <div class="bg-indigo-50 dark:bg-indigo-900/50 border border-indigo-200 dark:border-indigo-700 rounded-xl p-6">
                <div class="flex items-start gap-3">
                    <svg class="w-6 h-6 text-indigo-600 dark:text-indigo-400 mt-0.5 flex-shrink-0" fill="currentColor" viewBox="0 0 20 20">
                        <path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7-4a1 1 0 11-2 0 1 1 0 012 0zM9 9a1 1 0 000 2v3a1 1 0 001 1h1a1 1 0 100-2v-3a1 1 0 00-1-1H9z" clip-rule="evenodd"/>
                    </svg>
                    <div>
                        <h4 class="font-semibold text-indigo-900 dark:text-indigo-200 mb-2">💡 Budget Tips</h4>
                        <ul class="text-sm text-indigo-800 dark:text-indigo-200 space-y-1">
                            <li>• <strong>Start with historical data:</strong> Review your past spending to set realistic budgets</li>
                            <li>• <strong>Use the 50/30/20 rule:</strong> 50% needs, 30% wants, 20% savings</li>
                            <li>• <strong>Build in buffer:</strong> Add 10% extra for unexpected expenses</li>
                            <li>• <strong>Track regularly:</strong> Check your progress weekly to stay on target</li>
                            @if(!$category_id)
                                <li>• <strong>Overall budgets:</strong> Track total spending across all categories</li>
                            @else
                                <li>• <strong>Category budgets:</strong> Get detailed control over specific spending areas</li>
                            @endif
                        </ul>
                    </div>
                </div>
            </div>
            <!-- Form Actions -->
            <div class="flex items-center justify-between">
                <a href="{{ route('budget.index') }}" class="px-6 py-3 border border-neutral-300 dark:border-neutral-600 rounded-lg text-neutral-700 dark:text-neutral-300 font-semibold hover:bg-neutral-50 dark:hover:bg-neutral-700 transition">
                    Cancel
                </a>
                <button type="submit" class="px-8 py-3 bg-gradient-to-r from-indigo-600 to-purple-600 text-white rounded-lg font-semibold hover:shadow-lg transition transform hover:-translate-y-0.5 flex items-center gap-2">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/>
                    </svg>
                    {{ $isEdit ? 'Update Budget' : 'Create Budget' }}
                </button>
            </div>
        </form>
        <!-- Examples Section -->
        @if(!$isEdit && $categories->count() > 0)
            <div class="mt-8 bg-white dark:bg-neutral-800 rounded-xl shadow-md p-6">
                <h3 class="text-lg font-semibold text-neutral-800 dark:text-neutral-200 mb-4">Budget Examples</h3>
                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                    <div class="p-4 bg-neutral-50 dark:bg-neutral-700 rounded-lg">
                        <p class="font-semibold text-neutral-900 dark:text-neutral-200 mb-2">🍔 Food & Dining</p>
                        <p class="text-sm text-neutral-600 dark:text-neutral-400">Recommended: $400-600/month</p>
                        <p class="text-xs text-neutral-500 dark:text-neutral-400 mt-1">Includes groceries, restaurants, and coffee</p>
                    </div>
                    <div class="p-4 bg-neutral-50 dark:bg-neutral-700 rounded-lg">
                        <p class="font-semibold text-neutral-900 dark:text-neutral-200 mb-2">🚗 Transportation</p>
                        <p class="text-sm text-neutral-600 dark:text-neutral-400">Recommended: $200-400/month</p>
                        <p class="text-xs text-neutral-500 dark:text-neutral-400 mt-1">Gas, insurance, maintenance, public transit</p>
                    </div>
                    <div class="p-4 bg-neutral-50 dark:bg-neutral-700 rounded-lg">
                        <p class="font-semibold text-neutral-900 dark:text-neutral-200 mb-2">🎬 Entertainment</p>
                        <p class="text-sm text-neutral-600 dark:text-neutral-400">Recommended: $100-200/month</p>
                        <p class="text-xs text-neutral-500 dark:text-neutral-400 mt-1">Movies, concerts, hobbies, subscriptions</p>
                    </div>
                    <div class="p-4 bg-neutral-50 dark:bg-neutral-700 rounded-lg">
                        <p class="font-semibold text-neutral-900 dark:text-neutral-200 mb-2">🛒 Shopping</p>
                        <p class="text-sm text-neutral-600 dark:text-neutral-400">Recommended: $150-300/month</p>
                        <p class="text-xs text-neutral-500 dark:text-neutral-400 mt-1">Clothes, electronics, household items</p>
                    </div>
                </div>
                <p class="text-xs text-neutral-500 dark:text-neutral-400 mt-4 text-center">
                    * These are general guidelines. Adjust based on your income and lifestyle.
                </p>
            </div>
        @endif
    </div>
 

</div>