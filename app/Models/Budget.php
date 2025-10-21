<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Budget extends Model
{
    //
    protected $guarded =[];
    
    protected $casts=[
        'amount'=>'decimal:2',
        'month'=>'integer',
        'year'=>'integer'
    ];

    public function user(){
        return $this->belongsTo(User::class);
    }
    public function category(){
        return $this->belongsTo(Category::class);
    }
    

}
