<?php

namespace App\Model;

use Swagger\Annotations as SWG;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * @SWG\Definition(required={"question"}, type="object", @SWG\Xml(name="QuestionInputDto"))
 */
class QuestionInputDto
{
    /**
     * @Assert\NotBlank
     * @var string
     */
    public $question;

    /**
     * @var string[]
     */
    public $tags = [];
}
