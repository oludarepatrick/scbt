<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class StudentAnswer extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id', 'question_id', 'question_type', 'quiz_id',
        'answer_option', 'is_correct', 'test_session_id'
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function testSession()
    {
        return $this->belongsTo(TestSession::class);
    }

    public function question()
    {
        return $this->belongsTo(AiQuestion::class, 'question_id');
    }
    public function quiz()
    {
        return $this->belongsTo(QuizUser::class, 'quiz_id');
    }
}
