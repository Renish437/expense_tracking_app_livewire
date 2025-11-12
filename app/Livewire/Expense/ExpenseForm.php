<?php

namespace App\Livewire\Expense;

use App\Models\Expense;
use App\Models\Category;
use Livewire\Component;
use Livewire\Attributes\Computed;
use Illuminate\Support\Facades\Auth;

class ExpenseForm extends Component
{
    /* ------------------------------------------------------------------ *
     *  Public properties – bound to the form
     * ------------------------------------------------------------------ */
    public $expenseId;

    public $amount = '';
    public $title = '';
    public $description = '';
    public $date;
    public $category_id = '';
    public $type = 'one-time';               // one-time | recurring
    public $recurring_frequency = 'monthly'; // default for new recurring
    public $recurring_start_date;
    public $recurring_end_date;

    public $isEdit = false;

    /* ------------------------------------------------------------------ *
     *  Validation rules – built on the fly
     * ------------------------------------------------------------------ */
    protected function rules()
    {
        $rules = [
            'amount'      => 'required|numeric|min:0.01',
            'title'       => 'required|string|max:255',
            'description' => 'nullable|string',
            'date'        => 'required|date',
            'category_id' => 'nullable|exists:categories,id',
            'type'        => 'required|in:one-time,recurring',
        ];

        if ($this->type === 'recurring') {
            $rules = array_merge($rules, [
                'recurring_frequency'  => 'required|in:daily,weekly,monthly,yearly',
                'recurring_start_date' => 'required|date',
                'recurring_end_date'   => 'nullable|date|after:recurring_start_date',
            ]);
        }

        return $rules;
    }

    /* ------------------------------------------------------------------ *
     *  Mount – called on create and on edit
     * ------------------------------------------------------------------ */
    public function mount($expenseId = null)
    {
        if ($expenseId) {
            $this->isEdit    = true;
            $this->expenseId = $expenseId;
            $this->loadExpense();
        } else {
            // New expense – sensible defaults
            $today = now()->format('Y-m-d');
            $this->date = $today;
            $this->recurring_start_date = $today;
        }
    }

    /* ------------------------------------------------------------------ *
     *  Load an existing expense (edit mode)
     * ------------------------------------------------------------------ */
    public function loadExpense()
    {
        $expense = Expense::findOrFail($this->expenseId);

        if ($expense->user_id !== Auth::id()) {
            abort(403);
        }

        $this->fill($expense->only([
            'amount',
            'title',
            'description',
            'date',
            'category_id',
            'type',
            'recurring_frequency',
            'recurring_start_date',
            'recurring_end_date',
        ]));

        // Convert Carbon → Y-m-d for <input type="date">
        $this->date                = optional($expense->date)->format('Y-m-d');
        $this->recurring_start_date = optional($expense->recurring_start_date)->format('Y-m-d');
        $this->recurring_end_date   = optional($expense->recurring_end_date)->format('Y-m-d');
    }

    /* ------------------------------------------------------------------ *
     *  React when the user toggles One-time ↔ Recurring
     * ------------------------------------------------------------------ */
    public function updatedType()
    {
        $this->resetErrorBag(); // clear old errors

        // Re-validate the fields that belong to the new type
        $this->validateOnly('type');

        if ($this->type === 'recurring') {
            $this->validateOnly('recurring_frequency');
            $this->validateOnly('recurring_start_date');

            // Auto-fill start-date if it’s empty
            if (empty($this->recurring_start_date)) {
                $this->recurring_start_date = $this->date ?? now()->format('Y-m-d');
            }

            // Give a sensible default frequency
            if (empty($this->recurring_frequency)) {
                $this->recurring_frequency = 'monthly';
            }
        } else {
            // Clean recurring fields when switching back
            $this->recurring_frequency   = null;
            $this->recurring_start_date  = null;
            $this->recurring_end_date    = null;
        }
    }

    /* ------------------------------------------------------------------ *
     *  Categories – computed property (cached)
     * ------------------------------------------------------------------ */
    #[Computed]
    public function categories()
    {
        return Category::where('user_id', Auth::id())
            ->orderBy('name')
            ->get();
    }

    /* ------------------------------------------------------------------ *
     *  Save / Update
     * ------------------------------------------------------------------ */
    public function save()
    {
        // Full validation – rules are built with the current $type
        $this->validate();

        $data = [
            'user_id'     => Auth::id(),
            'amount'      => $this->amount,
            'title'       => $this->title,
            'description' => $this->description,
            'date'        => $this->date,
            'category_id' => $this->category_id ?: null,
            'type'        => $this->type,
        ];

        if ($this->type === 'recurring') {
            $data = array_merge($data, [
                'recurring_frequency'  => $this->recurring_frequency,
                'recurring_start_date' => $this->recurring_start_date,
                'recurring_end_date'   => $this->recurring_end_date,
            ]);
        } else {
            $data = array_merge($data, [
                'recurring_frequency'  => null,
                'recurring_start_date' => null,
                'recurring_end_date'   => null,
            ]);
        }

        if ($this->isEdit) {
            $expense = Expense::findOrFail($this->expenseId);
            if ($expense->user_id !== Auth::id()) {
                abort(403);
            }
            $expense->update($data);
            session()->flash('success', 'Expense updated successfully.');
        } else {
            Expense::create($data);
            session()->flash('success', 'Expense created successfully.');
        }

        // Livewire-aware redirect – works for both create & edit
        return $this->redirectRoute('expenses.index');
    }

    /* ------------------------------------------------------------------ *
     *  Render
     * ------------------------------------------------------------------ */
    public function render()
    {
        return view('livewire.expense.expense-form', [
            'categories' => $this->categories,
        ]);
    }
}
