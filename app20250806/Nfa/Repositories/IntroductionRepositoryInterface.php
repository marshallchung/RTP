<?php

namespace App\Nfa\Repositories;

use App\IntroductionType;

interface IntroductionRepositoryInterface
{
    public function getIntroductions(IntroductionType $type = null);

    public function getDashboardIntroductions();

    public function postIntroduction($data);
}
