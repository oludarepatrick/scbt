<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

use App\Models\AiQuestion;
use App\Models\Quiz;
use App\Models\User;
use App\Models\Result;
use App\Models\Curriculum;

class Curriculum extends Model
{
    protected $table = 'curriculums';

    use HasFactory;

    protected $fillable = ['curriculum_id','user_id', 'subject', 'class', 'content', 'name', 'time_left'];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function aiQuestions()
    {
        return $this->hasMany(AiQuestion::class, 'curriculum_id');
    }

    public function attempts()
    {
        return $this->hasMany(QuizUser::class, 'quiz_id');
    }

    public function subject()
    {
        return $this->belongsTo(Subject::class); // optional
    }

    public function quizzes()
    {
        return $this->hasMany(Quiz::class);
    }

}
