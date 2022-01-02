<?php

namespace App\Tests\Service;

use App\Entity\Movie;
use App\Repository\MovieRepository;
use App\Service\MovieService;
use PHPUnit\Framework\TestCase;

class MovieServiceTest extends TestCase
{
    public function testMockCountMovie()
    {
        $movie = new Movie();
        $movie->setName('Movie mock name');
        $movie->setName('Movie mock description');
        $movieRepositoryMock = $this->createMock(MovieRepository::class);
        $movieRepositoryMock->expects($this->once())
            ->method('findAll')
            ->willReturn([$movie, $movie]);

        $movieService = new MovieService($movieRepositoryMock);
        $number = $movieService->countMovie();
        $this->assertSame(2, $number);
    }

    public function testStubCountMovie()
    {
        $movie = new Movie();
        $movie->setName('Movie mock name');
        $movie->setName('Movie mock description');
        $movieRepositoryStub = $this->createStub(MovieRepository::class);
        $movieRepositoryStub
            ->method('findAll')
            ->willReturn([$movie, $movie]);

        $movieService = new MovieService($movieRepositoryStub);
        $number = $movieService->countMovie();
        $this->assertSame(2, $number);
    }
}
