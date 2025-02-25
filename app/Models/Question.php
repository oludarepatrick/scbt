<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\Answer;
use App\Models\Quiz;
use App\Models\Question;

class Question extends Model
{
    use HasFactory;
    protected $fillable = ['question_id','question','quiz_id','mfile_ext'];
    private $limit = 5;
    private $order = 'DESC';

    public function answers(){

        return $this->hasMany(Answer::class);
    }

    public function quiz(){
        return $this->belongsTo(Quiz::class);
    }

    public function storeQuestion($data){
        $data['quiz_id'] = $data['quiz'];
        return Question::create($data);
    }
    public function getQuestions(){
        return Question::orderBy('created_at',$this->order)->with('quiz')->paginate($this->limit);

    }

    public function getQuestionById($id){
        return Question::find($id);
    }

    public function findQuestion($id){
        return Question::find($id);
    }

    public function updateQuestion($id,$data){
        $question = Question::find($id);
        $question->question = $data['question'];
        $question->quiz_id = $data['quiz'];
        $question->mfile_ext = $data['mfile_ext'];
        $question->save();
        return $question;
    }

    public function deleteQuestion($id){
        Question::where('id',$id)->delete();
    }
}
