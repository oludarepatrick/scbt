<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Curriculum extends Model
{
    protected $table = 'curriculums';

    use HasFactory;

    protected $fillable = ['user_id', 'subject', 'class', 'content', 'name', 'time_left'];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function aiQuestions()
    {
        return $this->hasMany(AiQuestion::class);
    }

    public function attempts()
    {
        return $this->hasMany(QuizUser::class, 'quiz_id');
    }

    public function subject()
    {
        return $this->belongsTo(Subject::class); // optional
    }
}
