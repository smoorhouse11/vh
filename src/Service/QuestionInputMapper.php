<?php

namespace App\Service;

use App\Entity\Question;
use App\Model\QuestionInputDto;
use App\Model\QuestionSlug;

/**
 * Data mapper transforming question input to entity model.
 */
class QuestionInputMapper
{
    /**
     * @var TagResolver
     */
    private $tagResolver;

    /**
     * QuestionInputMapper constructor.
     *
     * @param TagResolver $tagResolver
     */
    public function __construct(TagResolver $tagResolver)
    {
        $this->tagResolver = $tagResolver;
    }

    /**
     * @param QuestionInputDto $inputDto
     *
     * @return Question
     */
    public function toQuestion(QuestionInputDto $inputDto): Question
    {
        $question = new Question();
        $question->setQuestion($inputDto->question);
        $question->setSlug(new QuestionSlug($inputDto->question));
        foreach ($this->tagResolver->resolveToEntities($inputDto->tags) as $tag) {
            $question->addTag($tag);
        }
        return $question;
    }
}
