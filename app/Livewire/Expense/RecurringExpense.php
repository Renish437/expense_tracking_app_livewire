<?php

namespace App\Livewire\Expense;

use App\Models\Expense;
use Livewire\Component;
use App\Models\Category;
use Livewire\Attributes\Title;
use Livewire\Attributes\Computed;
use Illuminate\Support\Facades\Auth;

#[Title("Recurring Expenses - ExpenseApp")]
class RecurringExpense extends Component
{
    public $showDeleteModal = false;
    public $expenseToDelete = null;

    public function confirmDelete($expenseId)
    {
        $this->expenseToDelete = $expenseId;
        $this->showDeleteModal = true;
    }

    public function deleteExpense()
    {
        if ($this->expenseToDelete) {
            $expense = Expense::findOrFail($this->expenseToDelete);
            if ($expense->user_id !== Auth::user()->id) {
                abort(403);
            }
            // delete the dependent expenses
            $expense->childExpenses()->delete();
            $expense->delete();

            session()->flash('message', 'Recurring Expense deleted successfully!');

            $this->showDeleteModal = false;
            $this->expenseToDelete = null;
        }
    }
    #[Computed()]
    public function recurringExpenses()
    {
        return Expense::with(['category', 'childExpenses'])
            ->forUser(Auth::user()->id)
            ->recurring()
            ->get();
    }
    #[Computed()]
    public function categories()
    {
        return Category::where('user_id', Auth::user()->id)
            ->orderBy('name')
            ->get();
    }
    #[Computed()]
    public function generatedThisMonth()
    {
        $month = now()->month;
        $year = now()->year;

        return $this->recurringExpenses()->sum(function ($expense) use ($month, $year) {
            // If actual child expenses exist, count them
            $childCount = $expense->childExpenses()
                ->whereMonth('date', $month)
                ->whereYear('date', $year)
                ->count();

            // If none exist yet, calculate virtual occurrences
            if ($childCount === 0) {
                $startDate = $expense->recurring_start_date;
                $endDate = $expense->recurring_end_date ?? now();

                $count = 0;
                $date = $startDate->copy();

                while ($date->month == $month && $date->lte($endDate)) {
                    $count++;
                    $date = match ($expense->recurring_frequency) {
                        'daily' => $date->addDay(),
                        'weekly' => $date->addWeek(),
                        'monthly' => $date->addMonth(),
                        'yearly' => $date->addYear(),
                        default => null, // ← replace break with null
                    };
                }

                return $count;
            }

            return $childCount;
        });
    }



    public function render()
    {
        return view('livewire.expense.recurring-expense', [
            'recurringExpenses' => $this->recurringExpenses(),
            'categories' => $this->categories(),
            'generatedThisMonth' => $this->generatedThisMonth(),
        ]);
    }
}
