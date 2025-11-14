<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class ClassModel extends Model
{
    protected $table = 'classes';
    use HasFactory;

    protected $fillable = [
        'name'
    ];
}
