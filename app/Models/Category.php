<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Category extends Model
{
    //
    protected $guarded=[];

    public function user(){
        return $this->belongsTo(User::class);
    }
    public function expenses(){
        return $this->hasMany(Expense::class);
    }
    public function budgets(){
        return $this->hasMany(Budget::class);
    }
    public function getTotalSpentForMonth($month,$year){
        return $this->expenses()
        ->whereMonth('date',$month)
        ->whereYear('date',$year)
        ->sum('amount');

    }

}
