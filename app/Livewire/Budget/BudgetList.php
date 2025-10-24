<?php
namespace App\Livewire\Budget;

use App\Models\Budget;
use App\Models\Category;
use Carbon\Carbon;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;
use Livewire\Attributes\Computed;
use Livewire\Attributes\Url;
use Livewire\Component;

class BudgetList extends Component
{
     #[Url(history:false)]
    public $selectedMonth;

    #[Url(history:false)]
    public $selectedYear;

    public function mount()
    {
        // Default values if no query params provided
        $this->selectedMonth = $this->selectedMonth ?? now()->month;
        $this->selectedYear = $this->selectedYear ?? now()->year;
    }

    public function budgets()
    {
        Log::info("Fetching budgets for user: " . Auth::user()->id . ", month: {$this->selectedMonth}, year: {$this->selectedYear}");
        $budgets = Budget::with('category')
            ->where('user_id', Auth::user()->id)
            ->where('month', $this->selectedMonth)
            ->where('year', $this->selectedYear)
            ->get()
            ->map(function ($budget) {
                $budget->spent = $budget->getSpentAmount();
                $budget->remaining = $budget->getRemainingAmount();
                $budget->percentage = $budget->getPercentageUsed();
                $budget->is_over = $budget->isOverBudget();
                return $budget;
            });
        Log::info("Found {$budgets->count()} budgets", ['budgets' => $budgets->toArray()]);
        return $budgets;
    }

    #[Computed]
    public function totalBudget()
    {
        return $this->budgets()->sum('amount');
    }

    #[Computed]
    public function totalSpent()
    {
        return $this->budgets()->sum('spent');
    }

    #[Computed]
    public function totalRemaining()
    {
        return $this->budgets()->sum('remaining');
    }

    #[Computed]
    public function overallPercentage()
    {
        if ($this->totalBudget() == 0) {
            return 0;
        }
        return round(($this->totalSpent() / $this->totalBudget()) * 100, 1);
    }

    #[Computed]
    public function categories()
    {
        return Category::where('user_id', Auth::user()->id)
            ->orderBy('name')
            ->get();
    }

    public function updatedSelectedMonth($value)
    {
        $value = intval($value);
        $this->selectedMonth = ($value >= 1 && $value <= 12) ? $value : now()->month;
        $this->resetValidation('selectedMonth');
        $this->clearComputedCache();
        Log::info("Updated selectedMonth to: {$this->selectedMonth}");
    }

    public function updatedSelectedYear($value)
    {
        $value = intval($value);
        $this->selectedYear = ($value >= 1900 && $value <= 2100) ? $value : now()->year;
        $this->resetValidation('selectedYear');
        $this->clearComputedCache();
        Log::info("Updated selectedYear to: {$this->selectedYear}");
    }

    public function previousMonth()
    {
        $date = Carbon::create($this->selectedYear, $this->selectedMonth, 1)->subMonth();
        $this->selectedMonth = $date->month;
        $this->selectedYear = $date->year;
        $this->clearComputedCache();
    }

    public function nextMonth()
    {
        $date = Carbon::create($this->selectedYear, $this->selectedMonth, 1)->addMonth();
        $this->selectedMonth = $date->month;
        $this->selectedYear = $date->year;
        $this->clearComputedCache();
    }

    private function clearComputedCache()
    {
        unset(
            $this->totalBudget,
            $this->totalSpent,
            $this->totalRemaining,
            $this->overallPercentage,
            $this->categories
        );
    }

    public function setCurrentMonth()
    {
        $this->selectedMonth = now()->month;
        $this->selectedYear = now()->year;
        $this->clearComputedCache();
    }

    public function deleteBudget($budgetId)
    {
        $budget = Budget::findOrFail($budgetId);
        if ($budget->user_id !== Auth::user()->id) {
            abort(403);
        }
        $budget->delete();
        session()->flash('message', 'Budget deleted successfully.');
    }

    public function render()
    {
        Log::info("Rendering BudgetList: month={$this->selectedMonth}, year={$this->selectedYear}, budgets_count={$this->budgets()->count()}");
        return view('livewire.budget.budget-list', [
            'budgets' => $this->budgets(),
            'totalBudget' => $this->totalBudget(),
            'totalSpent' => $this->totalSpent(),
            'totalRemaining' => $this->totalRemaining(),
            'overallPercentage' => $this->overallPercentage(),
            'categories' => $this->categories(),
        ]);
    }
}