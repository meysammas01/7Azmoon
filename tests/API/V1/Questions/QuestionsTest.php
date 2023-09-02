<?php

namespace Tests\API\V1\Questions;

use App\consts\QuestionStatus;
use Tests\TestCase;

class QuestionsTest extends TestCase
{
    public function setUp(): void
    {
        parent::setUp();
        $this->artisan('migrate:fresh');
    }

    public function test_ensure_we_can_create_a_new_question()
    {
        $quiz = $this->createQuiz()[0];
        $questionData = [
            'title' => 'what is PHP?',
            'options' => json_encode([
                1 => ['text' => 'PHP is a car', 'is_correct' => 0],
                2 => ['text' => 'PHP is a programming language', 'is_correct' => 1],
                3 => ['text' => 'PHP is a animal', 'is_correct' => 0],
                4 => ['text' => 'PHP is a toy', 'is_correct' => 0],
            ]),
            'is_active' => QuestionStatus::ACTIVE,
            'score' => 5,
            'quiz_id' => $quiz->getId(),
        ];

      $response = $this->call('POST', 'api/v1/questions', $questionData);
      $responseData = json_decode($response->getContent(), true)['data'];
      $this->assertEquals(201, $response->getStatusCode());
      $this->assertEquals($questionData['title'], $responseData['title']);
      $this->assertEquals($questionData['options'], $responseData['options']);
      $this->assertEquals($questionData['is_active'], $responseData['is_active']);
      $this->assertEquals($questionData['score'], $responseData['score']);
      $this->assertEquals($questionData['quiz_id'], $responseData['quiz_id']);

      $this->seeJsonStructure([
          'success',
          'message',
          'data' => [
              'title',
              'options',
              'score',
              'is_active',
              'quiz_id',
          ],
      ]);
    }
    public function test_ensure_we_can_delete_a_question () {
        $question = $this->createQuestion()[0];
        $response = $this->call('DELETE' , 'api/v1/questions',[
            'id' => $question->getId(),
        ]);
        $this->assertEquals(200, $response->getStatusCode());
        $this->seeJsonStructure([
            'success',
            'message',
            'data'
        ]);
    }
    public function test_ensure_we_can_get_questions () {
        $this->createQuestion(30);
        $pagesize = 3;
        $response = $this->call('GET', 'api/v1/questions',[
            'page' => 1,
            'pagesize' => $pagesize,
        ]);
        $responseData = json_decode($response->getContent(), true)['data'];
        $this->assertEquals(200, $response->getStatusCode());
        $this->assertEquals($pagesize, count($responseData));
        $this->seeJsonStructure([
            'success',
            'message',
            'data'
        ]);
    }
    public function test_ensure_we_can_get_filtered_questions () {
        $searchKey = 'What is golang?';
        $quiz = $this->createQuiz()[0];
        $this->createQuestion(1, [
            'title' => $searchKey,
            'is_active' => QuestionStatus::ACTIVE,
            'score' => 5,
            'quiz_id' => $quiz->getId(),
            'options' => json_encode([
                1 => ['text' => 'golang is a car', 'is_correct' => 0],
                2 => ['text' => 'golang is a programming language', 'is_correct' => 1],
                3 => ['text' => 'golang is a animal', 'is_correct' => 0],
                4 => ['text' => 'golang is a toy', 'is_correct' => 0],
            ]),
        ]);
        $response = $this->call('GET', 'api/v1/questions',[
            'search' => $searchKey,
            'page' => 1,
            'pagesize' => 3,
        ]);
        $responseData = json_decode($response->getContent(), true)['data'];
        foreach ($responseData as $question) {
            $this->assertEquals($question['title'], $searchKey);
        }
        $this->assertEquals(200, $response->getStatusCode());
        $this->seeJsonStructure([
            'success',
            'message',
            'data'
        ]);
    }
    public function test_ensure_we_can_update_a_question () {
        $question = $this->createQuestion()[0];

        $questionUpdatedData = [
            'id' => (string)$question->getId(),
            'title' => $question->getTitle() . 'updated',
            'score' => 30,
            'is_active' => $question->getIsActive(),
            'options' => json_encode($question->getOptions(), true),
            'quiz_id' => $question->getQuizId(),
        ];
        $response = $this->call('PUT', 'api/v1/questions', $questionUpdatedData);

        $responseData = json_decode($response->getContent(), true)['data'];

        $this->assertEquals(200, $response->getStatusCode());

        $this->assertEquals($questionUpdatedData['title'], $responseData['title']);
        $this->assertEquals($questionUpdatedData['score'], $responseData['score']);

        $this->seeJsonStructure([
            'success',
            'message',
            'data' => [
                'quiz_id',
                'title',
                'score',
                'is_active',
                'options'
            ],
        ]);
    }
}
