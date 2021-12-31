<?php

namespace App\Repository;

use App\Entity\Movie;
use Doctrine\Persistence\ManagerRegistry;

class MovieRepository extends BaseRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Movie::class);
    }
}
