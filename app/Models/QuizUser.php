<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class QuizUser extends Model
{
    use HasFactory;
    
   
    protected $table ='quiz_users';
    protected $fillable = ['quiz_id', 'curriculum_id', 'user_id', 'time_left','id','status', 'started_at', 'submitted_at'];

      public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function curriculum()
    {
        return $this->belongsTo(Curriculum::class, 'curriculum_id');
    }

    public function studentAnswers()
    {
        return $this->hasMany(StudentAnswer::class, 'test_session_id', 'id');
    }

    public function quiz()
    {
        return $this->belongsTo(Quiz::class);
    }

    
}
