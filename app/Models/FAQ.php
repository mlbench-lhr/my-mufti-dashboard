<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Carbon\Carbon;

class FAQ extends Model
{
    use HasFactory;

    protected $table = 'faqs'; // Ensure this matches your table name
    protected $fillable = ['question', 'answer']; // Ensure fields are fillable
    protected $casts = [
        'created_at' => "datetime:M d, Y",
    ];
}
