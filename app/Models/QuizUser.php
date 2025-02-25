<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class QuizUser extends Model
{
    use HasFactory;
    
   // protected $connection = 'mysql2';
    protected $table ='quiz_user';
    protected $fillable = ['quiz_id','user_id','id'];

    
}
