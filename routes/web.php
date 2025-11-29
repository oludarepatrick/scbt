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
use App\Http\Controllers\StaffSubjectController;
//New Ai CBT Controllers
use App\Http\Controllers\TeacherController;
use App\Http\Controllers\CurriculumController;
//use App\Http\Controllers\TeacherQuestionController;
use App\Http\Controllers\AiQuestionController;
//use App\Http\Controllers\TestSessionController;
use App\Http\Controllers\StudentAnswerController;
use App\Http\Controllers\StudentController;
use App\Http\Controllers\AiStudentController;
use App\Http\Controllers\SchoolSetupController;

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
    Route::post('/quiz/store', [QuizController::class, 'store'])->name('quiz.store');
    Route::post('/quiz/update', [QuizController::class, 'update'])->name('quiz.update');

    
    Route::post('loadstudresult', [App\Http\Controllers\ExamController::class, 'showResult'])->name('loadstudresult');
    Route::post('exam/loadsquizes2', [App\Http\Controllers\ExamController::class, 'loadQuizes2'])->name('loadsquizes2');
    
    Route::get('exam/re-assign', [App\Http\Controllers\ExamController::class, 'reAssignForm'])->name('re-assign');
    Route::post('exam/loadstudreasign', [App\Http\Controllers\ExamController::class, 'showStudForReassign'])->name('loadstudreasign');
    Route::post('exam/re-assigning', [App\Http\Controllers\ExamController::class, 'saveReAssigning'])->name('re-assigning');
    
    
    Route::post('question/loadquestion', [App\Http\Controllers\QuestionController::class, 'showQuestion'])->name('loadquestion');

    Route::middleware(['auth', 'isAdmin'])->group(function () {
    Route::get('/staff/subject/create', [StaffSubjectController::class, 'create'])->name('staffsubj.create');
    Route::post('/staff/subject/store', [StaffSubjectController::class, 'store'])->name('staffsubj.store');
   
     // Class Routes
    Route::get('/classes', [SchoolSetupController::class, 'showClasses'])->name('classes.index');
    Route::post('/classes', [SchoolSetupController::class, 'storeClass'])->name('classes.store');
    Route::post('/classes/{id}/update', [SchoolSetupController::class, 'updateClass'])->name('classes.update');
    Route::delete('/classes/{id}', [SchoolSetupController::class, 'destroyClass'])->name('classes.destroy');

    // Subject Routes
    Route::get('/subjects', [SchoolSetupController::class, 'showSubjects'])->name('subjects.index');
    Route::post('/subjects', [SchoolSetupController::class, 'storeSubject'])->name('subjects.store');
    Route::post('/subjects/{id}/update', [SchoolSetupController::class, 'updateSubject'])->name('subjects.update');
    Route::delete('/subjects/{id}', [SchoolSetupController::class, 'destroySubject'])->name('subjects.destroy');

    // School Info Routes
    Route::get('/info', [SchoolSetupController::class, 'showInfo'])->name('info.index');
    Route::post('/info', [SchoolSetupController::class, 'storeInfo'])->name('info.store');
    Route::post('/info/{id}/update', [SchoolSetupController::class, 'updateInfo'])->name('info.update');
    Route::delete('/info/{id}', [SchoolSetupController::class, 'destroyInfo'])->name('info.destroy');

});

    // New Ai CBT routes fpr teachers
    Route::prefix('admin')->middleware(['auth', 'isAdmin'])->group(function () {
    // Curriculum management
    Route::get('dashboard', [TeacherController::class, 'dashboard'])->name('dashboard');

    // Curriculum Upload
    Route::get('/curriculum/upload', [TeacherController::class, 'uploadCurriculumForm'])->name('teacher.curriculum.upload.form');
    Route::post('/curriculum/upload', [TeacherController::class, 'uploadCurriculum'])->name('teacher.curriculum.upload');


    Route::get('/questions/generate', [TeacherController::class, 'showQuestionGenerationForm'])->name('questions.generate.form');
    Route::post('questions/generate', [TeacherController::class, 'generateAIQuestions'])->name('questions.generate');
    Route::get('/questions/preview', [TeacherController::class, 'previewGeneratedQuestions'])->name('questions.preview');
    Route::post('questions/save', [TeacherController::class, 'saveGeneratedQuestions'])->name('questions.save');

  
    Route::get('questions', [TeacherController::class, 'myQuestions'])->name('questions.index');
    Route::get('questions/edit/{id}', [TeacherController::class, 'editQuestion'])->name('questions.edit');
    Route::delete('questions/delete/{id}', [TeacherController::class, 'deleteQuestion'])->name('questions.delete');
    Route::post('questions/update/{id}', [TeacherController::class, 'updateQuestion'])->name('questions.update');

    Route::get('/teacher/ai-questions', [TeacherController::class, 'aiQuestionList'])->name('teacher.ai_questions');
    Route::post('/teacher/ai-questions/{curriculum}/activate', [TeacherController::class, 'activateCurriculum'])->name('teacher.ai_questions.activate');
    Route::get('/teacher/ai-questions/view/{curriculum_id}', [TeacherController::class, 'viewCurriculumQuestions'])->name('teacher.ai_questions.view');
    Route::post('/teacher/ai-questions/delete/{curriculum_id}', [TeacherController::class, 'deleteCurriculumQuestions'])
    ->name('teacher.ai_questions.delete');
    //Maths Generated Questions
    Route::get('/teacher/ai-questions-maths', [TeacherController::class, 'aiMathsQuestionList'])->name('teacher.ai_questions_maths');
    Route::get('/teacher/ai-questions/view_maths/{curriculum_id}', [TeacherController::class, 'viewMathsCurriculumQuestions'])->name('teacher.ai_questions.view_maths');
    Route::get('/questions/preview_maths', [TeacherController::class, 'previewMathsGeneratedQuestions'])->name('questions.preview_maths');

   

    // Generate & manage teacher-curated questions
    Route::get('/questions/teacher', [TeacherController::class, 'dashboard'])->name('teacher_questions.dashboard');
    //Route::get('/questions/teacher/create', [TeacherQuestionController::class, 'create'])->name('teacher_questions.create');
    //Route::post('/questions/teacher', [TeacherQuestionController::class, 'store'])->name('teacher_questions.store');

    // AI question generation from curriculum
    Route::get('/questions/ai/generate', [AiQuestionController::class, 'showGenerateForm'])->name('ai_questions.generate');
    Route::post('/questions/ai/generate', [AiQuestionController::class, 'generate'])->name('ai_questions.store');
    Route::get('/questions/ai/preview/{curriculum_id}', [AiQuestionController::class, 'preview'])->name('ai.preview');
    Route::post('/questions/ai/submit', [AiQuestionController::class, 'store'])->name('ai_questions.submit');
    Route::get('/ai-questions', [AiQuestionController::class, 'index'])->name('ai_questions.index');
    Route::delete('/questions/ai/{id}', [AiQuestionController::class, 'destroy'])->name('ai_questions.destroy');
    Route::post('/questions/ai/{id}/update', [AiQuestionController::class, 'update'])->name('ai_questions.update');
    //Generate Maths Question Route
    Route::post('/questions/ai/generate_maths', [AiQuestionController::class, 'generateMaths'])->name('ai_questions.store_maths');
    Route::get('/questions/ai/generate_maths', [AiQuestionController::class, 'showGenerateFormMaths'])->name('ai_questions.generate_maths');
    Route::get('/questions/ai/preview_maths/{curriculum_id}', [AiQuestionController::class, 'preview_maths'])->name('ai.preview_maths');
        //CK Editor Image Upload Route
    Route::post('/ckeditor/upload', [AiQuestionController::class, 'upload'])->name('ckeditor.upload');

});

    Route::prefix('cbt')->middleware(['auth'])->group(function () {
    // Start test (AI or Teacher curated)
   
    // Submit answers
    Route::post('/test/submit/{sessionId}', [StudentAnswerController::class, 'submit'])->name('test.submit');

    // View past results

});
    
});

Route::get('/student/quizzes', [StudentController::class, 'showAvailableQuizzes'])->name('student.quizzes');
Route::get('/cbt/student/quiz/{curriculum}', [StudentController::class, 'takeQuiz'])->name('student.quiz.start');


//Route::get('/openai-test', [AIQuestionController::class, 'testOpenAI']);

//Route::get('/test-hf', [AIQuestionController::class, 'testOpenRouter']);

//Route::get('/check-key', [AIQuestionController::class, 'checkApiKey']);

//AI Exam Student Ends
Route::get('/ai-login', [AiStudentController::class, 'showLogin'])->name('ai.login');
Route::post('/ai-login', [AiStudentController::class, 'login'])->name('ai.login.submit');

/*Route::middleware(['auth'])->prefix('ai')->group(function () {
    Route::get('/dashboard', [AIStudentController::class, 'dashboard'])->name('ai.dashboard');
    Route::get('/quizzes', [AIStudentController::class, 'showAvailableQuizzes'])->name('ai.quizzes');
    Route::get('/quiz/{id}/start', [AIStudentController::class, 'start'])->name('ai.quiz.start');
    Route::get('/quiz/{id}/result', [AIStudentController::class, 'viewResult'])->name('ai.quiz.result');
    Route::post('/ai/quiz/{quizUser}/submit', [AIStudentController::class, 'submit'])->name('quiz.submit');
    Route::get('/ai/quiz/{quizUser}/finish', [AIStudentController::class, 'finish'])->name('quiz.finish');
    Route::post('/ai/quiz/{quizUserId}/next', [AIStudentController::class, 'nextAjax'])->name('quiz.next');
    Route::post('/ai/quiz/save-time/{id}', [AIStudentController::class, 'saveTime'])->name('quiz.save_time');
    Route::get('/ai/quiz/{quiz}/result', [AIStudentController::class, 'result'])->name('quiz.result');
    Route::get('/quiz/{quizId}/result/pdf', [AIStudentController::class, 'exportResultPdf'])->name('quiz.result.pdf');
});*/

Route::middleware(['auth'])->prefix('ai')->group(function () {

    Route::get('/dashboard', [AiStudentController::class, 'dashboard'])->name('ai.dashboard');
    Route::get('/quizzes', [AiStudentController::class, 'showAvailableQuizzes'])->name('ai.quizzes');
    Route::get('/quiz/{quizId}/start', [AiStudentController::class, 'start'])->name('ai.quiz.start');
    Route::post('/quiz/{quizUser}/submit', [AiStudentController::class, 'submit'])->name('quiz.submit');
    Route::post('/quiz/{quizUser}/next', [AiStudentController::class, 'nextAjax'])->name('quiz.next');

    //Route::post('/quiz/save-time/{quizUser}', [AIStudentController::class, 'saveTime'])->name('quiz.save_time');
    Route::post('/quiz/save-time', [AiStudentController::class, 'saveTime'])->name('quiz.saveTime');
    //Route::post('/quiz/{id}/save-time', [AIStudentController::class, 'saveTime'])->name('quiz.save_time');
    Route::get('/quiz-user/{quizUser}/finish', [AiStudentController::class, 'finish'])->name('quiz.finish');
    Route::get('/quiz-user/{quizUser}/result', [AiStudentController::class, 'result'])->name('quiz.result');
    Route::get('/quiz-user/{quizUser}/result/pdf', [AiStudentController::class, 'exportResultPdf'])->name('quiz.result.pdf');
    // routes/web.php (inside your auth group)
    Route::post('/quiz-user/{quizUser}/finish', [AiStudentController::class, 'finish'])->name('quiz.finish.post');

});
