<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Event extends Model
{
    use HasFactory;

    protected $fillable = [
        'title',
        'entry_price',
        'exit_price',
        'start_date',
        'comment',
    ];
    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
