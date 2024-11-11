<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class DeleteAccountRequest extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'reason',
        'status',
        'user_id',
    ];
    protected $casts = [
        'user_id' => 'integer',
        'status' => 'integer',
    ];
    protected $hidden = [
        'deleted_at',
    ];
}
