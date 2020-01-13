<?php

namespace App\Service;

use App\Entity\Tag;
use App\Repository\TagRepository;
use Doctrine\ORM\EntityManagerInterface;

/**
 * Processes lists of strings into managed tag entities.
 *
 * Tags are modeled as entities because it is likely that one would want
 * provide a method to search for questions related to them.
 */
class TagResolver
{
    /**
     * @var TagRepository
     */
    private $tagRepository;

    /**
     * @var EntityManagerInterface
     */
    private $entityManager;

    /**
     * TagResolver constructor.
     *
     * @param TagRepository          $tagRepository
     * @param EntityManagerInterface $em
     */
    public function __construct(TagRepository $tagRepository, EntityManagerInterface $em)
    {
        $this->tagRepository = $tagRepository;
        $this->entityManager = $em;
    }

    /**
     * @param string[] $tagNames
     *
     * @return Tag[]
     */
    public function resolveToEntities(array $tagNames): array
    {
        $resolvedEntities = [];

        foreach ($tagNames as $name) {
            $foundTag = $this->tagRepository->findOneBy(['name' => $name]);
            if (null === $foundTag) {
                $resolvedEntities[] = $this->createNewManagedEntity($name);
                continue;
            }

            $resolvedEntities[] = $foundTag;
        }

        return $resolvedEntities;
    }

    /**
     * @param string $name
     *
     * @return Tag
     */
    private function createNewManagedEntity(string $name): Tag
    {
        $newTag = new Tag();
        $newTag->setName($name);
        $this->entityManager->persist($newTag);
        $resolvedEntities[] = $newTag;

        return $newTag;
    }
}
