<?php

namespace App\Repository;

use App\Entity\Movie;
use Doctrine\ORM\EntityManagerInterface;

class MovieRepository extends BaseRepository
{
    public function __construct(EntityManagerInterface $entityManager)
    {
        parent::__construct($entityManager, Movie::class);
    }
}
