<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\QuizController;
use App\Http\Controllers\QuestionController;
use App\Http\Controllers\UserController;
use App\Http\Controllers\ExamController;
use App\Http\Controllers\SignupController;
use App\Http\Controllers\LogoutController;
use App\Http\Controllers\Admin\StaffLoginDetailsController;
use App\Http\Controllers\Admin\StudentLoginDetailsController;


/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/
/*Auth::routes([
    'register'=>false,
    'reset'=>false,
    'verify'=>false
]);*/



Auth::routes([
    //'signup'=>true,
    'reset'=>false,
    'verify'=>false
]);


Route::get('/cbt-logout', [App\Http\Controllers\LogoutController::class, 'perform'])->name('cbtLogout');


Route::get('/checkpoint/{id}', [App\Http\Controllers\JointController::class, 'checker'])->name('checkpoint/id');

Route::get('/home', [App\Http\Controllers\HomeController::class, 'index'])->name('home');
Route::get('/mycom', [App\Http\Controllers\ExamController::class, 'compo'])->name('mycom');
//Route::get('/home','HomeController@index')->name('home');
Route::get('user/quiz/{quizId}','App\Http\Controllers\ExamController@getQuizQuestions')->middleware('auth');
Route::post('quiz/create','App\Http\Controllers\ExamController@postQuiz')->middleware('auth');
Route::get('/result/user/{userId}/quiz/{quizId}','App\Http\Controllers\ExamController@viewResult')->middleware('auth');


// Signup Routes
Route::get('/signup', [SignupController::class, 'showRegistrationForm'])->name('signup.show');
Route::post('/signup', [SignupController::class, 'create'])->name('signup.save');

//Login Details Pages for both Staff student
Route::prefix('admin')->name('admin.')->middleware(['auth', 'isAdmin'])->group(function () {
    Route::get('/login-details/staff', [StaffLoginDetailsController::class, 'index'])->name('login-details.staff');
    Route::get('/login-details/student', [StudentLoginDetailsController::class, 'index'])->name('login-details.student');
});


Route::group(['middleware'=>'isAdmin'],function(){
    Route::get('/', function () {
        return view('admin.index');
        //Route::get('/adminHome', [App\Http\Controllers\HomeController::class, 'adminPage'])->name('adminHome');
    });
    Route::resource('/quiz', QuizController::class);
    Route::resource('/question', QuestionController::class);
    Route::resource('/user', UserController::class);

    Route::get('exam/assign', 'App\Http\Controllers\ExamController@create')->name('user.exam');
    Route::post('exam/assign', 'App\Http\Controllers\ExamController@assignExam')->name('exam.assign');
    Route::get('exam/user', 'App\Http\Controllers\ExamController@userExam')->name('view.exam'); 
    Route::post('cbt/result', 'App\Http\Controllers\ExamController@studResult')->name('display-result');
    
    Route::post('exam/remove', 'App\Http\Controllers\ExamController@removeExam')->name('exam.remove');

    
    Route::get('/quiz/{id}/questions', 'App\Http\Controllers\QuizController@question')->name('quiz.question');
    Route::get('exam/displayresult','App\Http\Controllers\ExamController@result')->name('displayresult');
    Route::get('result/{userId}/{quizId}','App\Http\Controllers\ExamController@userQuizresult');
    Route::post('result', 'App\Http\Controllers\ExamController@removeExam')->name('result');

    Route::post('exam/loadstud', [App\Http\Controllers\ExamController::class, 'showStudent'])->name('loadstud');
    Route::post('exam/loadsquizes', [App\Http\Controllers\ExamController::class, 'loadQuizes'])->name('loadsquizes');
    Route::post('quiz/loadsubjects', [App\Http\Controllers\QuizController::class, 'showSubjects'])->name('loadsubjects');
     
    Route::post('cbt/loadstudresult', [App\Http\Controllers\ExamController::class, 'showResult'])->name('loadstudresult');
    Route::post('exam/loadsquizes2', [App\Http\Controllers\ExamController::class, 'loadQuizes2'])->name('loadsquizes2');
    
    Route::get('exam/re-assign', [App\Http\Controllers\ExamController::class, 'reAssignForm'])->name('re-assign');
    Route::post('exam/loadstudreasign', [App\Http\Controllers\ExamController::class, 'showStudForReassign'])->name('loadstudreasign');
    Route::post('exam/re-assigning', [App\Http\Controllers\ExamController::class, 'saveReAssigning'])->name('re-assigning');
    
    
    Route::post('question/loadquestion', [App\Http\Controllers\QuestionController::class, 'showQuestion'])->name('loadquestion');
    
});
