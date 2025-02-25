<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Student extends Model
{
    use HasFactory;
    
    protected $connection = 'mysql2';
    protected $table ='student';
    protected $fillable = ['sn','student_id','surname','firstname','othername','sex','class','class_division','session'];

    
}
