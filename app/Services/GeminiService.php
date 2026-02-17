<?php

namespace App\Services;

use Google\Auth\Credentials\ServiceAccountCredentials;
use Google\Auth\HttpHandler\HttpHandlerFactory;
use GuzzleHttp\Client;
use Illuminate\Support\Facades\Log;

class GeminiService
{
    protected $httpClient;
    protected $projectId;
    protected $location;
    protected $accessToken;
    protected $tokenExpiry;

    public function __construct()
    {
        $this->httpClient = new Client();

        $this->projectId = config('services.google.project_id');
        $this->location = config('services.google.location');

        if (!$this->projectId) {
            throw new \Exception('GOOGLE_PROJECT_ID is not set in your .env file.');
        }
    }

    protected function getAccessToken(): string
    {
        if ($this->accessToken && time() < $this->tokenExpiry) {
            return $this->accessToken;
        }

        $keyFilePath = config('services.google.credentials_path');
        if (!file_exists($keyFilePath)) {
            throw new \Exception("Google credentials file not found at: {$keyFilePath}");
        }

        // Important: Use the correct target audience
        $targetAudience = 'https://us-central1-aiplatform.googleapis.com'; // For Gemini via Vertex AI

        $credentials = new ServiceAccountCredentials(
            null,
            $keyFilePath
        );

        $handler = HttpHandlerFactory::build();

        $jwt = $credentials->fetchAuthToken($handler);

        if (!isset($jwt['access_token'])) {
            throw new \Exception('Failed to fetch access token from Google.');
        }

        $this->accessToken = $jwt['access_token'];
        $this->tokenExpiry = time() + ($jwt['expires_in'] ?? 3600) - 30;

        Log::info('Successfully generated new Google API Access Token.');
        return $this->accessToken;
    }

    public function generateContent(string $prompt): array
{
    $token = $this->getAccessToken();

    $url = "https://{$this->location}-aiplatform.googleapis.com/v1/projects/{$this->projectId}/locations/{$this->location}/publishers/google/models/gemini-1.5-flash:predict";

    $response = $this->httpClient->post($url, [
        'headers' => [
            'Authorization' => 'Bearer ' . $token,
            'Content-Type'  => 'application/json',
        ],
        'json' => [
            'instances' => [
                ['prompt' => $prompt]
            ],
            'parameters' => [
                'temperature' => 0.7,
                'maxOutputTokens' => 1024,
            ]
        ],
        'http_errors' => false,
    ]);

    $statusCode = $response->getStatusCode();
    $body = json_decode($response->getBody()->getContents(), true);

    Log::debug('Gemini API raw response', ['status' => $statusCode, 'body' => $body]);

    if ($statusCode >= 400) {
        $errorMessage = $body['error']['message'] ?? 'An unknown API error occurred.';
        throw new \Exception("Google API Error: \"{$errorMessage}\"");
    }

    $text = $body['predictions'][0]['content'] ?? '';

    Log::debug('Raw Gemini content text', ['text' => $text]);

    if (empty($text)) {
        Log::warning('Gemini returned empty content.');
        return [];
    }

    return $this->parseQuestions($text);
}

    private function parseQuestions(string $text): array
    {
        $questions = [];
        $blocks = preg_split("/\n\s*\n/", trim($text));

        foreach ($blocks as $block) {
            $lines = explode("\n", trim($block));
            if (count($lines) < 5) continue;

            $questionText = array_shift($lines);
            $options = [];
            $answer = null;

            foreach ($lines as $line) {
                if (preg_match('/^([A-D])[\.\)]\s*(.*)/i', trim($line), $matches)) {
                    $options[$matches[1]] = $matches[2];
                } elseif (preg_match('/^Answer[:\-]?\s*([A-D])/i', $line, $matches)) {
                    $answer = $matches[1];
                }
            }

            if ($questionText && $options && $answer) {
                $questions[] = [
                    'question' => $questionText,
                    'options' => $options,
                    'answer' => $answer,
                ];
            }
        }

        return $questions;
    }
}
//Excellent code
