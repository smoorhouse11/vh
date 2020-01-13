<?php

namespace App\Model;

use Swagger\Annotations as SWG;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * @SWG\Definition(required={"answer", "rank"}, type="object", @SWG\Xml(name="AnswerInputDto"))
 */
class AnswerInputDto
{
    /**
     * @Assert\NotBlank()
     * @var string
     */
    public $answer;

    /**
     * @Assert\Positive()
     * @var integer
     */
    public $rank;
}
