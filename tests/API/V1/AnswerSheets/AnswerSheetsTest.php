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
    public function test_ensure_that_we_can_get_answer_sheets()
    {
        $this->createAnswerSheets(30);
        $pagesize = 3;
        $response = $this->call('GET', 'api/v1/answer-sheets', [
            'page' => 1,
            'pagesize' => $pagesize,
        ]);
        $data = json_decode($response->getContent(), true);

        $this->assertEquals($pagesize, count($data['data']));
        $this->assertEquals(200, $response->status());
        $this->seeJsonStructure([
            'success',
            'message',
            'data',
        ]);
    }
    public function test_ensure_we_can_get_filtered_answer_sheets()
    {
        $quiz = $this->createQuiz()[0];

        $searchKey = 50;
        $this->createAnswerSheets(1, [
            'quiz_id' => $quiz->getId(),
            'answers' => json_encode([
                'quiz_id' => $quiz->getId(),
                'answers' => json_encode([
                    1 => 3,
                    2 => 4,
                    3 => 1,
                ]),
                'status' => AnswerSheetsStatus::PASSED,
                'score' => $searchKey,
                'finished_at' => Carbon::now(),
            ])
            ]);
        $pagesize = 3;
        $response = $this->call('GET', 'api/v1/answer-sheets', [
            'page' => 1,
            'search' => (string)$searchKey,
            'pagesize' => $pagesize,
        ]);
        $data = json_decode($response->getContent(), true);

        foreach ($data['data'] as $answerSheet) {
            $this->assertEquals($answerSheet['score'], $searchKey);
        }
        $this->assertEquals(200, $response->status());
        $this->seeJsonStructure([
            'success',
            'message',
            'data',
        ]);
    }
}
