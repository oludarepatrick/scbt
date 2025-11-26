<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class AiQuestion extends Model
{
    protected $table = 'ai_questions';
    use HasFactory;

    protected $casts = [
    'options' => 'array',
    ];

    protected $fillable = [
    'user_id',
    'curriculum_id',
    'class',
    'subject',
    'source',
    'duration',
    'question_text',
    'option_a',
    'option_b',
    'option_c',
    'option_d',
    'correct_option',
];

    public function curriculum()
    {
        return $this->belongsTo(Curriculum::class);
    }
}
