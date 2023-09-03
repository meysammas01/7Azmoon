<?php

namespace Tests\API\V1\AnswerSheets;

use App\consts\AnswerSheetsStatus;
use Carbon\Carbon;
use Tests\TestCase;

class AnswerSheetsTest extends TestCase
{
    public function setUp(): void
    {
        parent::setUp();
        $this->artisan('migrate:fresh');
    }
    public function test_ensure_we_can_create_an_answer_sheet () {
        $quiz = $this->createQuiz()[0];

        $answerSheetData = [
          'quiz_id' => $quiz->getId(),
          'answers' => json_encode([
              1 => 3,
              2 => 4,
              3 => 1,
          ]),
            'status' => AnswerSheetsStatus::PASSED,
            'score' => 10,
            'finished_at' => Carbon::now(),
        ];

        $response = $this->call('POST', 'api/v1/answer-sheets', $answerSheetData);
        $responseData = json_decode($this->response->getContent(), true)['data'];
        $responseData['finished_at'] =  Carbon::parse($responseData['finished_at'])->format('Y-m-d H:i:s');
        $answerSheetData['finished_at'] = $answerSheetData['finished_at']->format('Y-m-d H:i:s');

 //     $this->seeInDatabase('answer_sheets', $answerSheetData);
//      $this->assertJson($responseData['answers']);


        $this->assertEquals($answerSheetData['quiz_id'], $responseData['quiz_id']);
        $this->assertEquals($answerSheetData['answers'], $responseData['answers']);
        $this->assertEquals($answerSheetData['status'], $responseData['status']);
        $this->assertEquals($answerSheetData['score'], $responseData['score']);
        $this->assertEquals($answerSheetData['finished_at'], $responseData['finished_at']);
        $this->assertEquals(201, $response->getStatusCode());
        $this->seeJsonStructure([
            'success',
            'message',
            'data' => [
                'quiz_id',
                'answers',
                'status',
                'score',
                'finished_at',
            ]

        ]);
    }
    public function test_ensure_we_can_delete_an_answer_sheet () {
        $answerSheet = $this->createAnswerSheets()[0];
        $response = $this->call('DELETE', 'api/v1/answer-sheets', [
            'id' => $answerSheet->getId(),
        ]);
        $this->assertEquals(200, $response->getStatusCode());
        $this->seeJsonStructure([
            'success',
            'message',
            'data',
        ]);

    }
}
