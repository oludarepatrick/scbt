<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class AiQuestion extends Model
{
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
    'question',
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
