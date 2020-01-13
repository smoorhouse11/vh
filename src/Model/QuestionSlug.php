<?php

namespace App\Model;

/**
 * Question slug value object.
 */
class QuestionSlug
{
    /**
     * @var string
     */
    private $slug;

    /**
     * QuestionSlug constructor.
     *
     * @param string $slug
     */
    public function __construct(string $slug)
    {
        $this->slug = $this->normalize($slug);
    }

    /**
     * @param string $question
     *
     * @return string
     */
    public function normalize(string $question): string
    {
        $question = rtrim(trim($question), '?');
        return preg_replace('/[^A-Za-z0-9]/', '_', strtolower($question));
    }

    /**
     * @return string
     */
    public function __toString(): string
    {
        return $this->slug;
    }
}
