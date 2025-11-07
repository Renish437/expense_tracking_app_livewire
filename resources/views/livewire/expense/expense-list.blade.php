<div class="min-h-screen">
    <!-- Header -->
    <div class="bg-primary-gradient shadow border-b border-neutral-300 dark:border-neutral-700">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-6">
            <div class="flex items-center justify-between">
                <div>
                    <h1 class="text-3xl font-bold text-white">Expenses</h1>
                    <p class="text-purple-100 mt-1">Manage and track your expenses</p>
                </div>
                <div class="flex items-center gap-4">
                    <!-- Dark Mode Toggle -->
                    

                    <a href="/expenses/create"
                       class="bg-white text-black  px-6 py-3 cursor-pointer rounded-xl font-bold shadow-md hover:shadow-lg transition flex items-center gap-2 hover:scale-105">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/>
                        </svg>
                        Add Expense
                    </a>
                </div>
            </div>
        </div>
    </div>

    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-8  dark:bg-neutral-900">
        <!-- Success Message -->
        @if (session()->has('message'))
            <div class="mb-6 bg-green-100 dark:bg-green-900/30 border border-green-300 dark:border-green-700 text-green-800 dark:text-green-200 px-5 py-4 rounded-xl flex items-center justify-between shadow-md">
                <span class="font-medium">{{ session('message') }}</span>
                <button onclick="this.parentElement.remove()" class="text-green-600 dark:text-green-400 hover:scale-110 transition">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/>
                    </svg>
                </button>
            </div>
        @endif

        <!-- Filters Section -->
        <div class="bg-white dark:bg-neutral-800 rounded-2xl shadow-sm p-6 mb-8 border border-neutral-200 dark:border-neutral-700">
            <div class="flex items-center justify-between mb-5">
                <h3 class="text-xl font-bold text-neutral-900 dark:text-white">Filters</h3>
                <button wire:click="$toggle('showFilters')"
                        class="text-purple-600 dark:text-purple-400 cursor-pointer hover:text-purple-700 dark:hover:text-purple-300 text-sm font-bold underline">
                    {{ $showFilters ? 'Hide' : 'Show' }} Filters
                </button>
            </div>

            @if($showFilters)
                <div class="grid grid-cols-1 md:grid-cols-4 gap-5">
                    <div>
                        <label class="block text-sm font-semibold text-neutral-700 dark:text-neutral-300 mb-2">Search</label>
                        <input type="text" wire:model.live.debounce.300ms="search" placeholder="Search expenses..."
                               class="w-full px-4 py-3 bg-neutral-50 dark:bg-neutral-700 border border-neutral-300 dark:border-neutral-600 rounded-xl focus:ring-2 focus:ring-purple-500 text-neutral-900 dark:text-white">
                    </div>
                    <div>
                        <label class="block text-sm font-semibold text-neutral-700 dark:text-neutral-300 mb-2">Category</label>
                        <select wire:model.live="selectedCategory"
                                class="w-full px-4 py-3 bg-neutral-50 dark:bg-neutral-700 border border-neutral-300 dark:border-neutral-600 rounded-xl focus:ring-2 focus:ring-purple-500 text-neutral-900 dark:text-white">
                            <option value="">All Categories</option>
                            @foreach($categories as $category)
                                <option value="{{ $category->id }}">{{ $category->name }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div>
                        <label class="block text-sm font-semibold text-neutral-700 dark:text-neutral-300 mb-2">Start Date</label>
                        <input type="date" wire:model.live="startDate"
                               class="w-full px-4 py-3 bg-neutral-50 dark:bg-neutral-700 border border-neutral-300 dark:border-neutral-600 rounded-xl focus:ring-2 focus:ring-purple-500 text-neutral-900 dark:text-white">
                    </div>
                    <div>
                        <label class="block text-sm font-semibold text-neutral-700 dark:text-neutral-300 mb-2">End Date</label>
                        <input type="date" wire:model.live="endDate"
                               class="w-full px-4 py-3 bg-neutral-50 dark:bg-neutral-700 border border-neutral-300 dark:border-neutral-600 rounded-xl focus:ring-2 focus:ring-purple-500 text-neutral-900 dark:text-white">
                    </div>
                </div> 

                <div class="mt-6 pt-5 border-t border-neutral-200 dark:border-neutral-700 flex flex-wrap items-center justify-between gap-4">
                    <div class="text-sm font-medium text-neutral-600 dark:text-neutral-400">
                        Showing <span class="text-purple-600 dark:text-purple-400 font-bold">{{ $expenses->count() }}</span>
                        of {{ $expenses->total() }} expenses
                        <span class="mx-2">•</span>
                        Total: <span class="text-green-600 dark:text-green-400 font-bold">${{ number_format($total, 2) }}</span>
                    </div>
                    <button wire:click="clearFilters"
                            class="text-sm font-bold text-purple-600 dark:text-purple-400 cursor-pointer hover:underline">
                        Clear Filters
                    </button>
                </div>
            @endif
        </div>

        <!-- Expenses Table -->
        <div class="bg-white dark:bg-neutral-800 rounded-2xl shadow-sm overflow-hidden border border-neutral-200 dark:border-neutral-700">
            <div class="overflow-x-auto">
                <table class="w-full">
                    <thead class="bg-neutral-50 dark:bg-neutral-700 border-b-2 border-neutral-200 dark:border-neutral-600">
                        <tr>
                            <th wire:click="sortBy('date')"
                                class="px-6 py-5 text-left text-xs font-bold text-neutral-600 dark:text-neutral-300 uppercase tracking-wider cursor-pointer hover:bg-neutral-100 dark:hover:bg-neutral-600 transition">
                                <div class="flex items-center gap-2">
                                    Date
                                    @if($sortBy === 'date')
                                        <svg class="w-4 h-4 transition {{ $sortDirection === 'asc' ? 'rotate-180' : '' }}" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"/>
                                        </svg>
                                    @endif
                                </div>
                            </th>
                            <th class="px-6 py-5 text-left text-xs font-bold text-neutral-600 dark:text-neutral-300 uppercase">Category</th>
                            <th wire:click="sortBy('title')"
                                class="px-6 py-5 text-left text-xs font-bold text-neutral-600 dark:text-neutral-300 uppercase cursor-pointer hover:bg-neutral-100 dark:hover:bg-neutral-600 transition">
                                <div class="flex items-center gap-2">
                                    Title
                                    @if($sortBy === 'title')
                                        <svg class="w-4 h-4 transition {{ $sortDirection === 'asc' ? 'rotate-180' : '' }}" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"/>
                                        </svg>
                                    @endif
                                </div>
                            </th>
                            <th class="px-6 py-5 text-left text-xs font-bold text-neutral-600 dark:text-neutral-300 uppercase">Description</th>
                            <th wire:click="sortBy('amount')"
                                class="px-6 py-5 text-right text-xs font-bold text-neutral-600 dark:text-neutral-300 uppercase cursor-pointer hover:bg-neutral-100 dark:hover:bg-neutral-600 transition">
                                <div class="flex items-center justify-end gap-2">
                                    Amount
                                    @if($sortBy === 'amount')
                                        <svg class="w-4 h-4 transition {{ $sortDirection === 'asc' ? 'rotate-180' : '' }}" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"/>
                                        </svg>
                                    @endif
                                </div>
                            </th>
                            <th class="px-6 py-5 text-right text-xs font-bold text-neutral-600 dark:text-neutral-300 uppercase">Actions</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-neutral-200 dark:divide-neutral-700">
                        @forelse($expenses as $expense)
                            <tr class="hover:bg-neutral-50 dark:hover:bg-neutral-700/50 transition" wire:key="expense-{{ $expense->id }}">
                                <td class="px-6 py-5">
                                    <div class="text-sm font-bold text-neutral-900 dark:text-white">
                                        {{ $expense->date->format('M d, Y') }}
                                    </div>
                                    <div class="text-xs text-neutral-500 dark:text-neutral-400">
                                        {{ $expense->date->format('l') }}
                                    </div>
                                </td>
                                <td class="px-6 py-5">
                                    @if($expense->category)
                                        <span class="inline-flex items-center gap-2 px-3 py-1.5 rounded-full text-sm font-bold"
                                              style="background-color: {{ $expense->category->color }}20; color: {{ $expense->category->color }};">
                                           <div>
                                            @if ($expense->category && $expense->category->icon)
                                                <flux:icon :name="$expense->category->icon" class="w-6 h-6 text-[{{ $expense->category->color }}]" />
                                            @else
                                                <flux:icon name="tag" class="w-6 h-6 text-white" />
                                            @endif
                                        </div>
                                            {{ $expense->category->name }}
                                        </span>
                                    @else
                                        <span class="text-neutral-400 dark:text-neutral-500 text-sm italic">Uncategorized</span>
                                    @endif
                                </td>
                                <td class="px-6 py-5">
                                    <div class="text-sm font-bold text-neutral-900 dark:text-white">{{ $expense->title }}</div>
                                    @if($expense->is_auto_generated)
                                        <span class="inline-flex items-center px-2.5 py-1 mt-1 rounded text-xs font-bold bg-orange-100 dark:bg-orange-900/30 text-orange-700 dark:text-orange-300">
                                            Auto-generated
                                        </span>
                                    @endif
                                </td>
                                <td class="px-6 py-5">
                                    <div class="text-sm text-neutral-600 dark:text-neutral-400 max-w-xs truncate">
                                        {{ $expense->description ?: '—' }}
                                    </div>
                                </td>
                                <td class="px-6 py-5 text-right">
                                    <div class="text-lg font-bold text-green-600 dark:text-green-400">
                                        ${{ number_format($expense->amount, 2) }}
                                    </div>
                                </td>
                                <td class="px-6 py-5 text-right">
                                    <div class="flex items-center justify-end gap-3">
                                        <a href="/expenses/{{ $expense->id }}/edit"
                                           class="text-purple-600 dark:text-purple-400 hover:text-purple-800 cursor-pointer dark:hover:text-purple-300 transition hover:scale-110">
                                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"/>
                                            </svg>
                                        </a>
                                        <button wire:click="deleteExpense({{ $expense->id }})"
                                                wire:confirm="Are you sure you want to delete this expense?"
                                                class="text-red-600 dark:text-red-400 hover:text-red-800 cursor-pointer dark:hover:text-red-300 transition hover:scale-110">
                                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/>
                                            </svg>
                                        </button>
                                    </div>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="6" class="px-6 py-16 text-center">
                                    <div class="flex flex-col items-center">
                                        <svg class="w-20 h-20 text-neutral-300 dark:text-neutral-600 mb-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/>
                                        </svg>
                                        <h3 class="text-2xl font-bold text-neutral-900 dark:text-white mb-3">No expenses found</h3>
                                        <p class="text-neutral-600 dark:text-neutral-400 mb-6 max-w-md">Start tracking your expenses to see them here.</p>
                                        <a href="/expenses/create"
                                           class="bg-primary-gradient hover:from-purple-700 hover:to-indigo-700 cursor-pointer text-white px-8 py-3 rounded-xl font-bold shadow-md hover:shadow-lg transition transform hover:-translate-y-1">
                                            Add Your First Expense
                                        </a>
                                    </div>
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            @if($expenses->hasPages())
                <div class="px-6 py-5 border-t border-neutral-200 dark:border-neutral-700 bg-neutral-50 dark:bg-neutral-700/50">
                    {{ $expenses->links() }}
                </div>
            @endif
        </div>
    </div>
</div>