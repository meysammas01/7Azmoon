<?php

namespace App\Http\Controllers\API\V1;

use App\Http\Controllers\API\contracts\APIController;
use App\repositories\Contracts\QuestionRepositoryInterface;
use App\repositories\Contracts\QuizRepositoryInterface;
use Illuminate\Http\Request;

class QuestionsController extends APIController
{
    public function __construct(private QuestionRepositoryInterface $questionRepository,
                                private QuizRepositoryInterface $quizRepository) {
    }
    public function store(Request $request) {
        $this->validate($request, [
            'title' => 'required|string',
            'score' => 'required|numeric',
            'is_active' => 'required|numeric',
            'quiz_id' => 'required|numeric',
            'options' => 'required|json',
        ]);

        if(! $this->quizRepository->find($request->quiz_id))
        {
            return $this->respondForbidden('آزمون وجود ندارد');
        }
        $question = $this->questionRepository->create([
            'title' => $request->title,
            'score' => $request->score,
            'is_active' => $request->is_active,
            'quiz_id' => $request->quiz_id,
            'options' => $request->options,
        ]);
        return $this->respondCreated('سوال ایجاد شد', [
           'title' => $question->getTitle(),
           'score' => $question->getScore(),
           'is_active' => $question->getIsActive(),
           'quiz_id' => $question->getQuizId(),
           'options' => json_encode($question->getOptions()),
        ]);
    }
    public function delete(Request $request) {
        $this->validate($request, [
           'id' => 'required|numeric',
        ]);
        if(!$this->questionRepository->find($request->id)) {
            return $this->respondForbidden('سوال وجود ندارد.');
        }
        if(!$this->questionRepository->delete($request->id)) {
            return $this->respondInternalError('سوال حذف نشد');
        }
        return $this->respondSuccess('سوال حذف شد', []);
    }
}
