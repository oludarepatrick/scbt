<?php

namespace App\Http\Controllers;


//use App\Services\GeminiService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Session;

use Illuminate\Support\Facades\Http;
use App\Models\Curriculum;
use App\Models\AiQuestion;
//use Google\Cloud\Storage\StorageClient;

class AiQuestionController extends Controller
{
    public function showGenerateForm()
    {
        $curriculums = Curriculum::where('user_id', auth()->id())->get();
        return view('backend.teacher_questions.generate', compact('curriculums'));
    }

public function testOpenRouter()
{
    $prompt = "Generate 3 multiple choice questions on Computer Studies with 4 options A–D and indicate the correct answer.";

    $response = Http::withHeaders([
        'Authorization' => 'Bearer ' . env('OPENROUTER_API_KEY'),
        'HTTP-Referer' => 'http://127.0.0.1:8000/', // required by OpenRouter
        'X-Title' => 'SCHOOLDRIVE CBT AI Generator'
    ])->post('https://openrouter.ai/api/v1/chat/completions', [
        'model' => 'deepseek/deepseek-r1-0528:free',
        'messages' => [
            ['role' => 'user', 'content' => $prompt]
        ],
        'temperature' => 0.7,
    ]);

    return response()->json([
        'status' => $response->status(),
        'body' => $response->json(),
    ]);
}


    //openroute AI STARTS HERE

    public function generate(Request $request)
{
    ini_set('max_execution_time', 180);

    $request->validate([
        'curriculum_id' => 'required|exists:curriculums,id',
        'number' => 'required|integer|min:1|max:150',
    ]);

    $curriculum = Curriculum::findOrFail($request->curriculum_id);

    // 🔥 Better, safer, formatting-stable prompt
    $prompt = "
Generate exactly {$request->number} multiple-choice objective questions
based on the {$curriculum->content} Lagos State, Nigeria school curriculum
for {$curriculum->class} level in the subject '{$curriculum->subject}'.

STRICT FORMAT RULES — FOLLOW EXACTLY:
-------------------------------------
Do NOT use markdown, no **bold**, no *, no numbering lists.
Return ONLY in this plain text format for every question:

Question X: <question text>
A) <option A>
B) <option B>
C) <option C>
D) <option D>
Correct Answer: <A/B/C/D>

IMPORTANT:
- Do NOT add explanations.
- Do NOT include any formatting except the structure above.
- Do NOT repeat the instructions.
- Every question must start with: Question X:
-------------------------------------
";

    // API Request
    $response = Http::withOptions(['verify' => false])
        ->withHeaders([
            'Authorization' => 'Bearer ' . env('OPENROUTER_API_KEY'),
            'X-Title'       => 'SchoolDrive CBT AI Generator'
        ])
        ->timeout(120)
        ->post('https://openrouter.ai/api/v1/chat/completions', [
            'model' => 'qwen/qwen2.5-coder-7b-instruct',
            'max_tokens' => 10000, // ✅ FIXED: Prevent token-limit credit erro
            'messages' => [
                ['role' => 'user', 'content' => $prompt],
            ],
        ]);

    $data = $response->json();

    // Log errors
    if ($response->failed()) {
        \Log::error("OpenRouter error", [
            'status' => $response->status(),
            'body'   => $response->body()
        ]);
        return back()->with('error', 'AI generation failed. Check logs.');
    }

    if (!isset($data['choices'][0]['message']['content'])) {
        return back()->with('error', 'Failed to generate questions.');
    }

    $content = $data['choices'][0]['message']['content'];

    // 🔥 PERFECT REGEX for enforced format
    preg_match_all( '/Question\s+\d+:\s*(.*?)\s*A\)\s*(.*?)\s*B\)\s*(.*?)\s*C\)\s*(.*?)\s*D\)\s*(.*?)\s*Correct Answer:\s*([A-D])/s', $content, $matches, PREG_SET_ORDER );

    if (empty($matches)) {
        \Log::error("No matches found in AI response", ['content' => $content]);
        return back()->with('error', 'Could not parse questions. Check AI response format.');
    }

    $saved = [];

    foreach ($matches as $match) {
        $correct = trim($match[6]);

        $saved[] = AiQuestion::create([
            'curriculum_id'  => $request->curriculum_id,
            'question_text'  => trim($match[1]),
            'option_a'       => trim($match[2]),
            'option_b'       => trim($match[3]),
            'option_c'       => trim($match[4]),
            'option_d'       => trim($match[5]),
            'correct_option' => $correct,
            'user_id'        => auth()->id(),
            'source'         => 'ai',
            'class'          => $curriculum->class,
            'subject'        => $curriculum->subject,
            'duration'       => $curriculum->time_left ?? 0,
        ]);
    }

    return redirect()->route('ai.preview', ['curriculum_id' => $request->curriculum_id])
                    ->with('success', count($saved) . ' questions generated and saved.');
}


   /* public function generate(Request $request)
{
    ini_set('max_execution_time', 180); // prevent timeout

    $request->validate([
        'curriculum_id' => 'required|exists:curriculums,id',
        'number' => 'required|integer|min:1|max:150',
    ]);

    $curriculum = Curriculum::findOrFail($request->curriculum_id);

    $prompt = "Generate {$request->number} multiple-choice objective questions"
            . "based on the {$curriculum->content} Lagos State, Nigeria school curriculum for "
            . "{$curriculum->class} level in the subject '{$curriculum->subject}'. "
            . "Each question should have 4 options labeled A, B, C, D, and indicate the correct answer. "
            . "Format:\n\n"
            . "Question X: ...?\n"
            . "A) ...\nB) ...\nC) ...\nD) ...\n"
            . "Correct Answer: <Letter>\n\n";

            /*$response = Http::withOptions(['verify' => false])
            ->withHeaders([
                'Authorization' => 'Bearer ' . env('OPENROUTER_API_KEY'),
                'HTTP-Referer'  => 'https://exam.schooldrive.com.ng',   // MUST be a real URL
                'X-Title'       => 'SchoolDrive CBT AI Generator'
            ])*/
            /*$response = Http::withOptions(['verify' => false])
            ->withHeaders([
                'Authorization' => 'Bearer ' . env('OPENROUTER_API_KEY'),
                'X-Title'       => 'SchoolDrive CBT AI Generator'
            ])
            ->timeout(120)
            ->post('https://openrouter.ai/api/v1/chat/completions', [
                'model' => 'openai/gpt-4.1-mini',
                'messages' => [
                    ['role' => 'user', 'content' => $prompt],
                ],
            ]);
        
        $data = $response->json();
        
        // Log full API error for debugging
        if ($response->failed()) {
            \Log::error("OpenRouter error", [
                'status' => $response->status(),
                'body'   => $response->body()
            ]);
            return back()->with('error', 'AI generation failed. Check logs.');
        }
        

    if (!isset($data['choices'][0]['message']['content'])) {
        return back()->with('error', 'Failed to generate questions.');
    }

    $content = $data['choices'][0]['message']['content'];

    // Flexible regex to handle **Question**, optional colons, and **Correct Answer**
    preg_match_all(
        '/\*?\*?Question\s+\d+:?\*?\*?\s*(.*?)\s*A\)\s*(.*?)\s*B\)\s*(.*?)\s*C\)\s*(.*?)\s*D\)\s*(.*?)\s*\*?\*?Correct Answer:\s*([A-D]|[^*\n]+)\*?\*?/s',
        $content,
        $matches,
        PREG_SET_ORDER
    );

    if (empty($matches)) {
        \Log::error("No matches found in AI response", ['content' => $content]);
        return back()->with('error', 'Could not parse questions. Check AI response format.');
    }

    $saved = [];

    foreach ($matches as $match) {
        $correct = trim($match[6]);

        // Normalize correct answer (handle cases where AI writes text instead of a letter)
        if (!in_array($correct, ['A','B','C','D'])) {
            $options = [
                'A' => trim($match[2]),
                'B' => trim($match[3]),
                'C' => trim($match[4]),
                'D' => trim($match[5]),
            ];
            foreach ($options as $key => $value) {
                if (stripos($value, $correct) !== false) {
                    $correct = $key;
                    break;
                }
            }
        }

        $saved[] = AiQuestion::create([
            'curriculum_id'  => $request->curriculum_id,
            'question_text'       => trim($match[1]),
            'option_a'       => trim($match[2]),
            'option_b'       => trim($match[3]),
            'option_c'       => trim($match[4]),
            'option_d'       => trim($match[5]),
            'correct_option' => $correct,
            'user_id'        => auth()->id(),
            'source'         => 'ai',
            'class'          => $curriculum->class,
            'subject'        => $curriculum->subject,
            // ✅ fixed: map time_left → duration, with fallback
            'duration'       => $curriculum->time_left ?? 0,
        ]);
    }

    return redirect()->route('ai.preview', ['curriculum_id' => $request->curriculum_id])
                    ->with('success', count($saved) . ' questions generated and saved.');
}*/



     

    public function preview($curriculum_id)
    {
    $curriculum = Curriculum::findOrFail($curriculum_id);

    $questions = AiQuestion::where('curriculum_id', $curriculum_id)
                           ->where('user_id', auth()->id())
                           ->latest()
                           ->get();


        return view('backend.teacher_questions.preview_questions', compact('questions', 'curriculum_id'));
    }

    

    public function store(Request $request)
    {
        $questions = session('ai_generated_questions');
        $curriculum_id = session('curriculum_id');

        if (!$questions || !$curriculum_id) {
            return redirect()->route('ai_questions.generate')->with('error', 'No questions to save.');
        }

        foreach ($questions as $q) {
            AiQuestion::create([
                'curriculum_id' => $curriculum_id,
                'question' => $q['question'],
                'option_a' => $q['option_a'],
                'option_b' => $q['option_b'],
                'option_c' => $q['option_c'],
                'option_d' => $q['option_d'],
                'correct_option' => $q['correct_option'],
            ]);
        }

        // Clear session
        session()->forget('ai_generated_questions');
        session()->forget('curriculum_id');

        return redirect()->route('ai_questions.index')->with('success', 'Questions saved successfully.');
    }

    public function destroy($id)
    {
        $question = AiQuestion::findOrFail($id);
        $question->delete();

        return back()->with('success', 'Question deleted successfully.');
    }

   public function update(Request $request, $id)
    {
        $request->validate([
            'question' => 'required|string',
            'options' => 'required|array',
            'correct_option' => 'required|string|in:A,B,C,D',
        ]);

        $question = AiQuestion::findOrFail($id);

        // Extract options
        $options = $request->input('options');

        $question->question_text = $request->input('question');
        $question->option_a = $options['A'] ?? null;
        $question->option_b = $options['B'] ?? null;
        $question->option_c = $options['C'] ?? null;
        $question->option_d = $options['D'] ?? null;
        $question->correct_option = $request->input('correct_option');
        $question->save();

        return response()->json([
            'success' => true,
            'message' => 'Question updated successfully.'
        ]);
    }

    //Generate Maths and Formular related Questions

    public function showGenerateFormMaths()
    {
        $curriculums = Curriculum::where('user_id', auth()->id())->get();
        return view('backend.teacher_questions.generate_maths', compact('curriculums'));
    }

    public function generateMaths(Request $request)
    {
        ini_set('max_execution_time', 180); // prevent timeout

        $request->validate([
            'curriculum_id' => 'required|exists:curriculums,id',
            'number' => 'required|integer|min:1|max:150',
        ]);

        $curriculum = Curriculum::findOrFail($request->curriculum_id);

        // 🔹 Enhanced Prompt
        $prompt = "Generate {$request->number} multiple-choice objective questions for "
                . "{$curriculum->class} level in the subject '{$curriculum->subject}'. "
                . "Each question should:\n"
                . "- Be wrapped in HTML if needed (e.g., <table>, <svg>, etc.)\n"
                . "- Use LaTeX (wrapped in double $$) for math formulas (e.g., quadratic equations, logs)\n"
                . "- Include visuals or descriptions for graphs, rectangles, or game theory diagrams when relevant\n"
                . "- Format:\n\n"
                . "Question X: ... (include LaTeX or HTML if needed)\n"
                . "A) ...\nB) ...\nC) ...\nD) ...\n"
                . "Correct Answer: <Letter>\n\n";

        $response = Http::withOptions(['verify' => false])
        ->withHeaders([
            'Authorization' => 'Bearer ' . env('OPENROUTER_API_KEY'),
            'HTTP-Referer' => 'http://127.0.0.1:8000/',
        ])
        ->timeout(120)
        ->post('https://openrouter.ai/api/v1/chat/completions', [
            'model' => 'openai/gpt-3.5-turbo',
            'messages' => [
                ['role' => 'user', 'content' => $prompt],
            ],
        ]);

        $data = $response->json();

        if (!isset($data['choices'][0]['message']['content'])) {
            return back()->with('error', 'Failed to generate questions.');
        }

        $content = $data['choices'][0]['message']['content'];

        // 🔹 Adjusted regex to allow HTML/LaTeX content in question and options
        preg_match_all('/Question\s+\d+:\s*(.*?)\nA\)\s*(.*?)\nB\)\s*(.*?)\nC\)\s*(.*?)\nD\)\s*(.*?)\nCorrect Answer:\s*([A-D])/s', $content, $matches, PREG_SET_ORDER);

        $saved = [];

        foreach ($matches as $match) {
            $saved[] = AiQuestion::create([
                'curriculum_id' => $request->curriculum_id,
                'question_text' => trim($match[1]),
                'option_a' => trim($match[2]),
                'option_b' => trim($match[3]),
                'option_c' => trim($match[4]),
                'option_d' => trim($match[5]),
                'correct_option' => trim($match[6]),
                'user_id' => auth()->id(),
                'source' => 'ai',
                'class' => $curriculum->class,
                'subject' => $curriculum->subject,
                'duration' => $curriculum->duration,
            ]);
        }

        return redirect()->route('ai.preview_maths', ['curriculum_id' => $request->curriculum_id])
                        ->with('success', 'Questions generated and saved.');
    }

    public function preview_maths($curriculum_id)
    {
    $curriculum = Curriculum::findOrFail($curriculum_id);

    $questions = AiQuestion::where('curriculum_id', $curriculum_id)
                           ->where('user_id', auth()->id())
                           ->latest()
                           ->get();


        return view('backend.teacher_questions.preview_questions_maths', compact('questions', 'curriculum_id'));
    }

}

/*public function testOpenAI() openai code start here
{
    $response = Http::withToken(env('OPENAI_API_KEY'))
        ->post('https://api.openai.com/v1/chat/completions', [
            'model' => 'gpt-3.5-turbo',
            'messages' => [
                ['role' => 'user', 'content' => 'Say hi from Laravel using HTTP client'],
            ],
        ]);

    return response()->json($response->json());
}

public function showGenerateForm()
    {
        $curriculums = Curriculum::where('user_id', auth()->id())->get();
        return view('backend.teacher_questions.generate', compact('curriculums'));
    }

public function generate(Request $request) 
{
    $request->validate([
        'curriculum_id' => 'required|exists:curriculums,id',
        'number' => 'required|integer|min:1|max:50',
    ]);

    // Retrieve the actual curriculum content
    $curriculum = Curriculum::where('id', $request->curriculum_id)
                            ->where('user_id', auth()->id())
                            ->firstOrFail();

    $subject = $curriculum->subject;

    $prompt = "Generate {$request->number} multiple-choice CBT questions based on the subject: '{$subject}'.
Each question should have four options (A–D) and include the correct answer in the following format:

Q: Question here?
A. Option A
B. Option B
C. Option C
D. Option D
Answer: C";

    // Call OpenAI API
    $response = Http::withToken(env('OPENAI_API_KEY'))->post('https://api.openai.com/v1/chat/completions', [
        'model' => 'gpt-3.5-turbo',
        'messages' => [
            ['role' => 'user', 'content' => $prompt],
        ],
        'temperature' => 0.7,
    ]);
    dd($response->json());
    $content = $response->json()['choices'][0]['message']['content'] ?? 'No response from AI.';

    return view('backend.teacher_questions.preview_questions', [
        'curriculum' => $curriculum,
        'questions' => $content,
    ]);
}

}*/ //openai code here

    //protected $geminiService;

    // By using dependency injection here, Laravel automatically creates
    // the GeminiService for you. This is better than calling 'new GeminiService()'.
    /*public function __construct(GeminiService $geminiService)
    {
        $this->geminiService = $geminiService;
        set_time_limit(120);

        
    }

    /**
     * This is the method that will receive the API call.
     * Note: I've renamed it from your original 'generate' to match the standard
     * Laravel resource controller naming convention and the previous example.
     * Your route should point to this method:
     * Route::post('/admin/questions/ai/generate', [AiQuestionController::class, 'generateQuestions']);
     */
   /* public function generate5(Request $request)
    {
        // 1. It's good practice to validate the incoming request.
        $validated = $request->validate([
            'prompt' => 'required|string|max:2000',
        ]);

        try {
            // 2. Call the service with the validated prompt.
            $result = $this->geminiService->generateContent($validated['prompt']);

            // 3. Return the successful result as JSON.
            // You can process the $result here before sending it back if needed.
            return response()->json([
                'success' => true,
                'data' => $result,
            ]);

            dd($result);

        } catch (\Exception $e) {
            // 4. If anything goes wrong, log the detailed error for debugging...
            Log::error('Gemini Service Exception: ' . $e->getMessage());

            // ...and return a user-friendly error message.
            return response()->json([
                'success' => false,
                'error' => 'Failed to generate content. ' . $e->getMessage(),
            ], 500); // Use a 500 status code for server errors.
        }
        dd($result);
    }



    public function index()
    {
        $questions = AIQuestion::where('user_id', auth()->id())
            ->orderBy('created_at', 'desc')
            ->paginate(20);

        return view('backend.ai_questions.index', compact('questions'));
    }
    // Show form to select curriculum and number of questions
     

public function generate()
{
    $storage = new StorageClient([
        'projectId' => 'deep-bivouac-452707-h3', // Replace with your actual project ID
    ]);

    // Example: list buckets (for test)
    foreach ($storage->buckets() as $bucket) {
        echo $bucket->name() . "<br>";
    }
}



    public function generate6(Request $request, GeminiService $gemini)
    {
        
        $gemini = new GeminiService();
        $result = $gemini->generateContent("Create a simple curriculum on the history of computers for grade 5 students.");
        dd($result);
        

        $request->validate([
        'curriculum_id' => 'required|exists:curriculums,id',
        ]);

        $curriculum = Curriculum::findOrFail($request->curriculum_id);
        
        //dd($curriculum);
       

        // Build the AI prompt
        $prompt = "Generate 5 multiple-choice questions for the following Nigerian school curriculum topic.\n";
        $prompt .= "Class: {$curriculum->class}\n";
        $prompt .= "Subject: {$curriculum->subject}\n";
        $prompt .= "Topic: {$curriculum->content}\n";
        if ($curriculum->objective) {
            $prompt .= "Objective: {$curriculum->objective}\n";
        }
        $prompt .= "Each question should have options A to D, and include the correct answer labeled 'Answer: A/B/C/D'.\n";
        $prompt .= "Format:\n";
        $prompt .= "1. What is ...?\nA. Option 1\nB. Option 2\nC. Option 3\nD. Option 4\nAnswer: A\n";

       // dd($prompt);

        try {
            $questions = $gemini->generateContent($prompt);
//dd($questions);
            foreach ($questions as $q) {
                AIQuestion::create([
                    'question' => $q['question'],
                    'options' => json_encode($q['options']),
                    'answer' => $q['answer'],
                    'subject' => $curriculum->subject,
                    'class' => $curriculum->class,
                    'topic' => $curriculum->content,
                    'created_by' => auth()->id(),
                ]);
            }
            
            return back()->with('success', count($questions) . ' AI questions generated and saved.');

        } catch (\Exception $e) {
            return back()->with('error', $e->getMessage());
        }
    }

    // Preview the generated questions
    public function preview()
    {
        $questions = Session::get('generated_questions', []);
        return view('backend.teacher_questions.preview_questions', compact('questions'));
    }

    // Save questions to database
    public function submit(Request $request)
    {
        $request->validate([
        'time_minutes' => 'required|numeric|min:1',
         ]);
        $questions = Session::get('generated_questions', []);
        $curriculumId = Session::get('curriculum_id');

        foreach ($questions as $q) {
            AIQuestion::create([
                'user_id' => auth()->id(),
                'curriculum_id' => $curriculumId,
                'class' => $q['class'],
                'subject' => $q['subject'],
                'question' => $q['question'],
                'option_a' => $q['option_a'],
                'option_b' => $q['option_b'],
                'option_c' => $q['option_c'],
                'option_d' => $q['option_d'],
                'correct_option' => $q['correct_answer'],
                 'time_minutes' => $request->time_minutes, // Add this
            ]);
        }

        Session::forget('generated_questions');
        Session::forget('curriculum_id');

        return redirect()->route('teacher_questions.dashboard')->with('success', 'AI questions saved successfully!');
        
    }*/