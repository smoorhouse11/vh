<?php

namespace App\Service;

use App\Entity\Answer;
use App\Model\AnswerInputDto;

/**
 * Data mapper transforming answer input to entity model.
 */
class AnswerInputMapper
{
    /**
     * @param AnswerInputDto $inputDto
     *
     * @return Answer
     */
    public function toAnswer(AnswerInputDto $inputDto): Answer
    {
        $answer = new Answer();
        $answer->setAnswer($inputDto->answer);
        $answer->setRank($inputDto->rank);

        return $answer;
    }
}
