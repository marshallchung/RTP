<?php

namespace App\Nfa\Repositories;

use App\Question;

class QuestionRepository implements QuestionRepositoryInterface
{

    public function updateScoreType($questionnaireId, $force = false)
    {
        $scoreTypes = [
            '基本指標' => 'basic',
            '進階指標' => 'advanced',
        ];
        $basicScoreQuestions = Question::whereQuestionnaireId($questionnaireId)->where('content', '基本指標')->get();
        foreach ($basicScoreQuestions as $advancedScoreQuestion) {
            $query = Question::whereQuestionnaireId($questionnaireId)
                ->where('code', 'like', $advancedScoreQuestion->code . '%');
            if (!$force) {
                $query->whereNull('score_type');
            }
            $query->update(['score_type' => $scoreTypes[$advancedScoreQuestion->content]]);
        }
        $advancedScoreQuestions = Question::whereQuestionnaireId($questionnaireId)->where('content', '進階指標')->get();
        foreach ($advancedScoreQuestions as $advancedScoreQuestion) {
            $query = Question::whereQuestionnaireId($questionnaireId)
                ->where('code', 'like', $advancedScoreQuestion->code . '%');
            if (!$force) {
                $query->whereNull('score_type');
            }
            $query->update(['score_type' => $scoreTypes[$advancedScoreQuestion->content]]);
        }
    }

    public function updateAllScoreType($force = false)
    {
        $questionnaireIds = Question::groupBy('questionnaire_id')->pluck('questionnaire_id');
        foreach ($questionnaireIds as $questionnaireId) {
            $this->updateScoreType($questionnaireId, $force);
        }
    }
}
