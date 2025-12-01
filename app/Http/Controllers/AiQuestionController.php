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
- Ensure to include the instructions needed for question if necessary.
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
            'model' => 'mistralai/mistral-7b-instruct',
            'max_tokens' => 2000, // ✅ FIXED: Prevent token-limit credit erro
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

