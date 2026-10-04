<?php

use App\Services\ResumeAnanlicesService;
use Illuminate\Support\Facades\Http;

beforeEach(function () {
    config()->set('services.openrouter.api_key', 'test-api-key');
});

it('analyzes OpenRouter text-block content', function () {
    Http::fake([
        'openrouter.ai/*' => Http::response([
            'id' => 'response-123',
            'model' => 'test/model',
            'choices' => [[
                'finish_reason' => 'stop',
                'message' => [
                    'content' => [
                        ['type' => 'text', 'text' => '{"resume":{"education":"Degree",'],
                        ['type' => 'text', 'text' => '"summary":"Summary","skills":"Skills","experience":"Experience"}}'],
                    ],
                ],
            ]],
        ]),
    ]);

    $result = app(ResumeAnanlicesService::class)->analyzeText('Resume text');

    expect($result['resume'])->toBe([
        'education' => 'Degree',
        'summary' => 'Summary',
        'skills' => 'Skills',
        'experience' => 'Experience',
    ]);
});

it('includes safe response metadata when OpenRouter returns no content', function () {
    Http::fake([
        'openrouter.ai/*' => Http::response([
            'id' => 'response-456',
            'model' => 'test/model',
            'choices' => [[
                'finish_reason' => 'length',
                'message' => [
                    'role' => 'assistant',
                    'refusal' => 'Unable to complete the request.',
                ],
            ]],
        ]),
    ]);

    try {
        app(ResumeAnanlicesService::class)->analyzeText('Private resume text');
        test()->fail('Expected an exception when the model returns no content.');
    } catch (UnexpectedValueException $exception) {
        expect($exception->getMessage())
            ->toContain('"response_id":"response-456"')
            ->toContain('"finish_reason":"length"')
            ->not->toContain('Private resume text')
            ->not->toContain('test-api-key');
    }
});
