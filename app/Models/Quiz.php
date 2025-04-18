<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\Question;
use App\Models\Quiz;
use App\Models\User;
use App\Models\Result;
class Quiz extends Model
{
    use HasFactory;

    //protected $fillable = ['name','description','minutes'];
    protected $fillable = ['name','description','minutes', 'class_id', 'arm', 'subject_id','status','sessions','terms'];

    public function questions(){
        return $this->hasMany(Question::class);
    }

    public function users(){
        return $this->belongsToMany(User::class,'quiz_user');
    }

    public function storeQuiz($data){
        return Quiz::create($data);
    }

    public function allQuiz(){
        return Quiz::all();
    }

    public function getQuizById($id){
        return Quiz::find($id);
    }

    public function updateQuiz($data,$id){
        return Quiz::find($id)->update($data);

    }

    public function deleteQuiz($id){
        return Quiz::find($id)->delete();
    }

    public function assignExam($data){
        $quizId= $data['quiz_id'];
        $quiz = Quiz::find($quizId);
        $userId = $data['user_id'];
        return $quiz->users()->syncWithoutDetaching($userId);
    }

    public function hasQuizAttempted(){
        $attemptQuiz = [];
        $authUser = auth()->user()->stud_id;
        $user = Result::where('user_id',$authUser)->get();
        foreach($user as $u){
            array_push($attemptQuiz,$u->quiz_id);
        }
        return $attemptQuiz;
    }
}
