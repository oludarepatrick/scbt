<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Subject extends Model
{
    use HasFactory;
    
    protected $connection = 'mysql2';
    protected $table ='staffsubj';
    protected $fillable = ['id','class','subject', 'staff_id', 
        'class_arm'];

    
}