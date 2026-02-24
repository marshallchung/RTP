<?php

namespace App\Nfa\Repositories;

interface QuestionRepositoryInterface
{
    public function updateScoreType($questionnaireId, $force = false);

    public function updateAllScoreType($force = false);
}
