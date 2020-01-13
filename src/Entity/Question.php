<?php

namespace App\Entity;

use App\Model\QuestionSlug;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use Swagger\Annotations as SWG;

/**
 * Question entity model.
 *
 * Answers modeled unidirectionally here as the user should have to
 * traverse the Question aggregate in order to access an Answer.
 *
 * @ORM\Entity
 * @ORM\Table(name="questions")
 */
class Question implements \JsonSerializable
{
    /**
     * @ORM\Id
     * @ORM\Column(type="integer")
     * @ORM\GeneratedValue(strategy="AUTO")
     * @SWG\Property(type="integer")
     *
     * @var integer
     */
    private $id;

    /**
     * @ORM\Column(type="string")
     * @SWG\Property(type="string")
     * @var string
     */
    private $question;

    /**
     * @ORM\Column(type="string")
     * @SWG\Property(type="string")
     * @var QuestionSlug
     */
    private $slug;

    /**
     * @ORM\ManyToMany(targetEntity="Tag")
     * @ORM\JoinTable(name="question_tags",
     *      joinColumns={@ORM\JoinColumn(name="question_id", referencedColumnName="id")},
     *      inverseJoinColumns={@ORM\JoinColumn(name="tag_id", referencedColumnName="id")}
     *      )
     *
     * @var Tag[]
     */
    private $tags;

    /**
     * @ORM\OneToMany(targetEntity="Answer", mappedBy="question", cascade={"persist"})
     *
     * @var Answer[]
     */
    private $answers;

    /**
     * Question constructor.
     */
    public function __construct()
    {
        $this->answers = new ArrayCollection();
        $this->tags = new ArrayCollection();
    }

    /**
     * @return int|null
     */
    public function getId(): ?int
    {
        return $this->id;
    }

    /**
     * @param int $id
     */
    public function setId(int $id): void
    {
        $this->id = $id;
    }

    /**
     * @return string
     */
    public function getQuestion(): string
    {
        return $this->question;
    }

    /**
     * @param string $question
     */
    public function setQuestion(string $question): void
    {
        $this->question = $question;
    }

    /**
     * @return string
     */
    public function getSlug(): string
    {
        return $this->slug;
    }

    /**
     * @param QuestionSlug|string $slug
     */
    public function setSlug($slug): void
    {
        if ($slug instanceof QuestionSlug) {
            $this->slug = $slug;
            return;
        }

        $this->slug = new QuestionSlug($slug);
    }

    /**
     * @return Collection
     */
    public function getAnswers(): Collection
    {
        return $this->answers;
    }

    /**
     * @param Answer $answer
     */
    public function addAnswer(Answer $answer)
    {
        $answer->setQuestion($this);
        $this->answers->add($answer);
    }

    /**
     * @return Collection
     */
    public function getTags(): Collection
    {
        return $this->tags;
    }

    /**
     * @param Tag $tag
     */
    public function addTag(Tag $tag): void
    {
        $this->tags->add($tag);
    }

    /**
     * @return array
     */
    public function jsonSerialize()
    {
        $answersMap = $this->getAnswers()->map(
            function (Answer $answer) {
                return [
                    'id' => $answer->getId(),
                    'rank' => $answer->getRank(),
                    'answer' => $answer->getAnswer()
                ];
            }
        );

        $tagsMap = $this->getTags()->map(
            function (Tag $tag) {
                return $tag->getName();
            }
        );

        return [
            "id" => $this->getId(),
            "question" => $this->getQuestion(),
            "answers" => $answersMap,
            "slug" => $this->getSlug(),
            "tags" => $tagsMap
        ];
    }
}
