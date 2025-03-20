<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Carbon\Carbon;

class FAQ extends Model
{
    use HasFactory;

    protected $table = 'faqs';
    protected $fillable = ['question', 'answer'];

    protected $casts = [
        'created_at' => "datetime:M d, Y",
    ];
    
    public function getConnectionName()
    {
        return request()->is('api/testing/*') ? 'testing_db' : 'mysql';
    }
}
