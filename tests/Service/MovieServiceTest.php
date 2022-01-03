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
        $number = $movieService->totalMovie();
        $this->assertSame(2, $number);
    }

    public function testStubCountMovie()
    {
        $stub = $this->getMockBuilder(MovieService::class)->setMethods(['totalMovie'])->disableOriginalConstructor()->getMock();
        $stub->method('totalMovie')->will($this->onConsecutiveCalls(1, 2));

        $countNextMovie = $stub->nextTotalMovie();
        $this->assertSame(2, $countNextMovie);

        $countNextMovie = $stub->nextTotalMovie();
        $this->assertSame(3, $countNextMovie);
    }
}
