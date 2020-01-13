<?php

namespace App\Controller;

use App\Entity\Question;
use App\Model\AnswerInputDto;
use App\Model\QuestionInputDto;
use App\Model\QuestionSlug;
use App\Repository\QuestionRepository;
use App\Service\AnswerInputMapper;
use App\Service\QuestionInputMapper;
use Doctrine\ORM\OptimisticLockException;
use Doctrine\ORM\ORMException;
use FOS\RestBundle\Controller\AbstractFOSRestController;
use FOS\RestBundle\Controller\Annotations as Rest;
use FOS\RestBundle\Request\ParamFetcher;
use FOS\RestBundle\View\View;
use Nelmio\ApiDocBundle\Annotation\Model;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\ParamConverter;
use Swagger\Annotations as SWG;
use Symfony\Component\HttpKernel\Exception\HttpException;
use Symfony\Component\Validator\ConstraintViolationListInterface;

/**
 * Operation actions on Question models.
 */
class QuestionController extends AbstractFOSRestController
{
    /**
     * @var QuestionRepository
     */
    private $questionRepo;

    /**
     * @var AnswerInputMapper
     */
    private $answerInputMapper;

    /**
     * @var QuestionInputMapper
     */
    private $questionInputMapper;

    /**
     * QuestionController constructor.
     *
     * @param QuestionRepository  $questionRepo
     * @param AnswerInputMapper   $answerInputNormalizer
     * @param QuestionInputMapper $questionInputNormalizer
     */
    public function __construct(
        QuestionRepository $questionRepo,
        AnswerInputMapper $answerInputNormalizer,
        QuestionInputMapper $questionInputNormalizer
    )
    {
        $this->questionRepo = $questionRepo;
        $this->answerInputMapper =$answerInputNormalizer;
        $this->questionInputMapper = $questionInputNormalizer;
    }

    /**
     * @Rest\Post("/api/questions")
     * @ParamConverter("inputDto", converter="fos_rest.request_body")
     *
     * @param QuestionInputDto                 $inputDto
     * @param ConstraintViolationListInterface $validationErrors
     *
     * @return View
     *
     * @throws ORMException
     * @throws OptimisticLockException
     * @throws HttpException
     *
     * @SWG\Post(
     *     path="/api/questions",
     *     summary="Add a new question to the system",
     *     consumes={"application/json"},
     *     produces={"application/json"},
     *     @SWG\Parameter(
     *         name="body",
     *         in="body",
     *         description="Question input",
     *         required=true,
     *         @SWG\Schema(ref=@Model(type=QuestionInputDto::class)),
     *     ),
     *     @SWG\Response(
     *         response=400,
     *         description="Invalid input",
     *     ),
     *     @SWG\Response(
     *         response=200,
     *         description="Question added",
     *         @Model(type=Question::class)
     *     )
     * )
     */
    public function createQuestion(QuestionInputDto $inputDto, ConstraintViolationListInterface $validationErrors)
    {
        $this->handleValidationErrors($validationErrors);

        /** @var Question $question */
        $existingQuestion = $this->questionRepo->findOneBy(['slug' => new QuestionSlug($inputDto->question)]);
        if (null !== $existingQuestion) {
            throw new HttpException(409, "The question, ". $inputDto->question . " already exists.");
        }

        $question = $this->questionInputMapper->toQuestion($inputDto);
        $this->questionRepo->save($question);

        return View::create($question);
    }

    /**
     * @Rest\Get("/api/questions")
     *
     * @Rest\QueryParam(name="limit", requirements="\d+", default="25", description="Records returned per page")
     * @Rest\QueryParam(name="offset", requirements="\d+", default="0", description="Zero-indexed number of records to skip")
     *
     * @param ParamFetcher $paramFetcher
     * @SWG\Get(
     *   description="Retrieve a paginated list of questions in the system.",
     *   @SWG\Response(
     *     description="List of question objects, paginated",
     *     response=200,
     *     @SWG\Schema(
     *       type="array",
     *       @SWG\Items(ref=@Model(type=Question::class))
     *     )
     *   )
     * )
     *
     * @return View
     */
    public function getQuestions(ParamFetcher $paramFetcher)
    {
        $questions = $this->questionRepo->getPage(
            $paramFetcher->get('limit'),
            $paramFetcher->get('offset')
        );

        return View::create($questions);
    }

    /**
     * @Rest\Post("/api/questions/{questionSlug}/answers")
     * @ParamConverter("inputDto", converter="fos_rest.request_body")
     * @SWG\Post(
     *     path="/api/questions/{questionSlug}/answers",
     *     summary="Add a new answer to a question",
     *     consumes={"application/json"},
     *     produces={"application/json"},
     *     @SWG\Parameter(
     *         name="body",
     *         in="body",
     *         description="Answer input",
     *         required=true,
     *         @SWG\Schema(ref=@Model(type=AnswerInputDto::class)),
     *     ),
     *     @SWG\Parameter(
     *         name="questionSlug",
     *         in="path",
     *         description="Unique question slug",
     *         required=true,
     *         type="string"
     *     ),
     *     @SWG\Response(
     *         response=400,
     *         description="Invalid input",
     *     ),
     *     @SWG\Response(
     *         response=200,
     *         description="Answer added to question",
     *         @Model(type=Question::class)
     *     )
     * )
     * @param AnswerInputDto                   $inputDto
     * @param string                           $questionSlug
     * @param ConstraintViolationListInterface $validationErrors
     *
     * @return View
     * @throws ORMException
     * @throws OptimisticLockException
     */
    public function createAnswer(AnswerInputDto $inputDto, string $questionSlug, ConstraintViolationListInterface $validationErrors)
    {
        $this->handleValidationErrors($validationErrors);

        /** @var Question $question */
        $question = $this->questionRepo->findOneBy(['slug' => $questionSlug]);

        if (null === $question) {
            throw new HttpException(404, "Question $questionSlug not found");
        }

        $question->addAnswer($this->answerInputMapper->toAnswer($inputDto));
        $this->questionRepo->save($question);

        return View::create($question);
     }

    /**
     * @param ConstraintViolationListInterface $validationErrors
     *
     * @throws HttpException
     */
     private function handleValidationErrors(ConstraintViolationListInterface $validationErrors)
     {
         if (count($validationErrors) > 0) {
             $errs = [];
             foreach ($validationErrors as $error) {
                 $errs [] = $error->getMessage();
             }

             throw new HttpException(400, implode(',', $errs));
         }
     }
}
