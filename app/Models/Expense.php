<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Attributes\Scope;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Expense extends Model
{
    //
    use HasFactory,SoftDeletes;
     protected $guarded =[];

     protected $casts=[
        'amount'=>'decimal:2',
        'date'=>'date',
        'recurring_start_date'=>'date',
        'recurring_end_date'=>'date',
        'is_auto_generated'=>'boolean'
     ];

    public function user(){
        return $this->belongsTo(User::class);
    }
    public function category(){
        return $this->belongsTo(Category::class);
    }
    public function parentExpense(){
        return $this->belongsTo(Expense::class,'parent_expense_id');
    }
    public function childExpenses(){
        return $this->hasMany(Expense::class,'parent_expense_id');
    }
    #[Scope]
    public function forUser($query,$userId){
        return $query->where('user_id',$userId);
    }
    #[Scope]
    public function recurring($query){
        return $query->where('type','recurring');
    }
    #[Scope]
    public function oneTime($query){
        return $query->where('type','one-time');
    }
    #[Scope]
    public function inMonth($query,$month,$year){
        return $query->whereMonth('date',$month)
        ->whereYear('date',$year);
    }
    #[Scope]
    public function inDateRange($query,$startDate,$endDate){
        return $query->whereBetween('date',[$startDate,$endDate]);
    }

    public function isRecurring():bool{
        return $this->type === "recurring";
    }
    public function shouldGenerateNextOccurance():bool{
        if(!$this->isRecurring()){
            return false;
        }
        if($this->recurring_end_date && now()->isAfter($this->recurring_end_date)){
            return false;
        }
        return true;

    }



}
