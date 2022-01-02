<?php

namespace App\Service;

use App\Repository\MovieRepository;

class MovieService
{
    /**
     * @var MovieRepository
     */
    protected $movieRepository = null;

    public function __construct(MovieRepository $movieRepository)
    {
        $this->movieRepository = $movieRepository;
    }

    public function countMovie(): int
    {
        return count($this->movieRepository->findAll());
    }
}
