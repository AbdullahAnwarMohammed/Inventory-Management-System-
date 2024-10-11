<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class DailyExpense extends Model
{
    use HasFactory;
    protected $fillable = ['amount', 'description', 'albunud_id'];

    public function albunud(){
        return $this->belongsTo(Albunud::class);
    }
}
