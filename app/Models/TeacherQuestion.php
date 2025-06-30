<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class TeacherQuestion extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id', 'class', 'subject', 'question_text',
        'option_a', 'option_b', 'option_c', 'option_d', 'option_e',
        'correct_option', 'is_ai_generated', 'enabled'
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
