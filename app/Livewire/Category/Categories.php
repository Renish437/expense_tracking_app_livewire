<?php

namespace App\Livewire\Category;

use Livewire\Component;
use App\Models\Category;
use Livewire\WithPagination;
use Illuminate\Support\Facades\Auth;
use Livewire\Attributes\Computed;

class Categories extends Component
{
    use WithPagination;

    public $name = '';
    public $color = '#3B82F6';
    public $icon = '';
    public $isEditing = false;
    public $editingId = null;
    public $iconSearch = '';

    public $colors = [
        '#3B82F6', '#8B5CF6', '#EC4899', '#F59E0B', '#10B981',
        '#EF4444', '#06B6D4', '#F97316', '#14B8A6', '#6366F1',
        '#A855F7', '#D946EF', '#FBBF24', '#059669', '#0EA5E9',
        '#7C3AED', '#E11D48', '#84CC16', '#F43F5E',
    ];

    // ✅ 50 VALID FLUX ICONS (TESTED)
    public $popularIcons = [
        // Navigation
        'home', 'squares-2x2', 'folder', 'folder-open', 'document-text', 'book-open',
        'cog-6-tooth', 'chevron-up-down', 'bars-3', 'adjustments-horizontal',
        
        // Finance
        'currency-dollar', 'credit-card', 'wallet', 'banknotes', 'shopping-cart',
        'shopping-bag', 'tag', 'receipt-percent',
        
        // Work
        'calendar-days', 'star', 'briefcase', 'clipboard-document-check', 'inbox-stack',
        'chart-bar', 'chart-pie', 'presentation-chart-line',
        
        // Communication
        'chat-bubble-left-right', 'envelope', 'phone', 'user-group', 'bell', 'heart',
        'user', 'users',
        
        // System
        'shield-check', 'lock-closed', 'key', 'globe-alt', 'cloud', 'information-circle',
        'exclamation-triangle', 'x-circle',
        
        // Misc
        'gift', 'sparkles', 'flag', 'fire', 'face-smile',
    ];

    protected $listeners = ['categorySaved' => '$refresh'];

    public function mount()
    {
        $this->resetForm();
    }

    public function resetForm()
    {
        $this->name = '';
        $this->color = '#3B82F6';
        $this->icon = '';
        $this->isEditing = false;
        $this->editingId = null;
        $this->iconSearch = '';
        $this->resetValidation();
    }

    public function getFilteredIconsProperty()
    {
        if (empty($this->iconSearch)) {
            return collect($this->popularIcons);
        }
        return collect($this->popularIcons)
            ->filter(fn($icon) => str_contains(strtolower($icon), strtolower($this->iconSearch)));
    }

    // ✅ VALIDATE ICONS
    public function validateIcon()
    {
        if (empty($this->icon)) return true;
        
        $allValidIcons = $this->popularIcons; // Use your exact 50 icons
        $isValid = collect($allValidIcons)->contains($this->icon);
        
        if (!$isValid) {
            $this->addError('icon', 'Invalid icon name');
            return false;
        }
        return true;
    }

    // 🚀 CREATE - DISPATCH TOAST EVENT
public function saveCategory()
{
    $this->validate([
        'name' => 'required|string|max:255',
        'color' => 'required|string|max:7',
    ]);

    if (!$this->validateIcon()) {
        $this->dispatch('alert', [
            'type' => 'error',
            'message' => 'Invalid icon name. Please choose from the grid.'
        ]);
        return;
    }

    if ($this->isEditing) {
        Category::find($this->editingId)->update([
            'name' => $this->name, 'color' => $this->color, 'icon' => $this->icon
        ]);
        $this->dispatch('alert', [
            'type' => 'success',
            'message' => 'Category updated successfully!'
        ]);
    } else {
        Category::create([
            'name' => $this->name, 'color' => $this->color, 'icon' => $this->icon,
            'user_id' => Auth::user()->id,
        ]);
        $this->dispatch('alert', [
            'type' => 'success',
            'message' => 'Category created successfully!'
        ]);
    }

    $this->resetForm();
    $this->dispatch('categorySaved');
}


    public function editCategory($id)
    {
        $category = Category::findOrFail($id);
        $this->name = $category->name;
        $this->color = $category->color;
        $this->icon = $category->icon;
        $this->isEditing = true;
        $this->editingId = $id;
        $this->iconSearch = '';
        $this->resetValidation();
    }

    // 🚀 DELETE - DISPATCH TOAST EVENT
    public function deleteCategory($id)
    {
       $category= Category::findOrFail($id);
        if($category->user_id !== Auth::user()->id){
            abort(403);
        }
        if($category->expenses()->count() > 0){
         $this->dispatch('alert', [
            'type' => 'success',
            'message' => 'Can not delete category with existing expenses!'
        ]);
        return;
        }
        
        $category->delete();
        $this->dispatch('alert', [
            'type' => 'success',
            'message' => 'Category deleted successfully!'
        ]);
        $this->dispatch('categorySaved');
    }
    public function cancelEdit(){
         $this->reset(['name','color','icon','editingId','isEditing']);
         $this->color='#3B82F6';
    }
    #[Computed]
    public function categories(){
        return Category::where('user_id', Auth::user()->id)
                ->withCount('expenses')->latest()->get();
    }

    public function render()
    {
        return view('livewire.category.categories', [
            'categories' => $this->categories,
            'filteredIcons' => $this->filteredIcons,
        ]);
    }
}