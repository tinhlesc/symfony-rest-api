<?php

namespace App\Tests\Controller;

use App\DataFixtures\MovieFixtures;
use App\Entity\Movie;
use Symfony\Component\HttpFoundation;

class MovieWebTestCase extends BaseWebTestCase
{
    /**
     * @var string
     */
    private $accessToken;

    public function setUp(): void
    {
        parent::setUp();

        $client = $this->getApiClient();
        $this->accessToken = $this->getAccessToken($client->getPublicId(), $client->getSecret());
    }

    public function testGetMovieAction()
    {
        $movieFixtures = new MovieFixtures();
        $this->loadFixture($movieFixtures);

        $this->client->request(
            HttpFoundation\Request::METHOD_GET,
            '/api/movies',
            [],
            [],
            [
                'HTTP_ACCEPT' => 'application/json',
                'HTTP_AUTHORIZATION' => sprintf('Bearer %s', $this->accessToken),
            ]
        );

        $this->assertEquals(HttpFoundation\Response::HTTP_OK, $this->client->getResponse()->getStatusCode());
        $data = json_decode($this->client->getResponse()->getContent(), true);
        $this->assertIsArray($data);
        $this->assertCount(1, $data);
        $movie = $data[0];
        $this->assertSame('Movie name', $movie['name']);
        $this->assertSame('Movie description', $movie['description']);
    }

    public function testViewMovieAction()
    {
        $movieFixtures = new MovieFixtures();
        $this->loadFixture($movieFixtures);

        $movieRepository = $this->entityManager->getRepository(Movie::class);
        $movie = $movieRepository->findOneBy(['name' => 'Movie name']);

        $this->client->request(
            HttpFoundation\Request::METHOD_GET,
            '/api/movies/'.$movie->getId(),
            [],
            [],
            [
                'HTTP_ACCEPT' => 'application/json',
                'HTTP_AUTHORIZATION' => sprintf('Bearer %s', $this->accessToken),
            ]
        );

        $this->assertEquals(HttpFoundation\Response::HTTP_OK, $this->client->getResponse()->getStatusCode());
        $data = json_decode($this->client->getResponse()->getContent(), true);
        $this->assertIsArray($data);
        $this->assertSame('Movie name', $data['name']);
        $this->assertSame('Movie description', $data['description']);
    }

    public function testPostMovieAction()
    {
        $payload = [
            'name' => 'Movie name',
            'description' => 'Movie description',
        ];
        $this->client->request(
            HttpFoundation\Request::METHOD_POST,
            '/api/movies',
            [],
            [],
            [
                'HTTP_ACCEPT' => 'application/json',
                'HTTP_AUTHORIZATION' => sprintf('Bearer %s', $this->accessToken),
            ],
            json_encode($payload)
        );

        $this->assertEquals(HttpFoundation\Response::HTTP_CREATED, $this->client->getResponse()->getStatusCode());

        $movieRepository = $this->entityManager->getRepository(Movie::class);
        $movie = $movieRepository->findOneBy(['name' => 'Movie name']);
        $this->assertNotEmpty($movie);
        $this->assertSame('Movie name', $movie->getName());
        $this->assertSame('Movie description', $movie->getDescription());
    }

    public function testPutMovieAction()
    {
        $movieFixtures = new MovieFixtures();
        $this->loadFixture($movieFixtures);

        $movieRepository = $this->entityManager->getRepository(Movie::class);
        $movie = $movieRepository->findOneBy(['name' => 'Movie name']);

        $payload = [
            'name' => 'Movie name update',
            'description' => 'Movie description update',
        ];
        $this->client->request(
            HttpFoundation\Request::METHOD_PUT,
            '/api/movies/'.$movie->getId(),
            [],
            [],
            [
                'HTTP_ACCEPT' => 'application/json',
                'HTTP_AUTHORIZATION' => sprintf('Bearer %s', $this->accessToken),
            ],
            json_encode($payload)
        );

        $this->assertEquals(HttpFoundation\Response::HTTP_NO_CONTENT, $this->client->getResponse()->getStatusCode());
        $this->entityManager->refresh($movie);
        $movie = $movieRepository->findOneBy(['name' => 'Movie name update']);
        $this->assertSame($payload['name'], $movie->getName());
        $this->assertSame($payload['description'], $movie->getDescription());

        $payload = [
            'name' => 'Movie name',
        ];
        $this->client->request(
            HttpFoundation\Request::METHOD_PATCH,
            '/api/movies/'.$movie->getId(),
            [],
            [],
            [
                'HTTP_ACCEPT' => 'application/json',
                'HTTP_AUTHORIZATION' => sprintf('Bearer %s', $this->accessToken),
            ],
            json_encode($payload)
        );

        $this->entityManager->refresh($movie);
        $movie = $movieRepository->findOneBy(['name' => 'Movie name']);
        $this->assertSame('Movie name', $movie->getName());
        $this->assertSame('Movie description update', $movie->getDescription());
    }

    public function testDeleteMovieAction()
    {
        $movieFixtures = new MovieFixtures();
        $this->loadFixture($movieFixtures);

        $movieRepository = $this->entityManager->getRepository(Movie::class);
        $movie = $movieRepository->findOneBy(['name' => 'Movie name']);

        $this->client->request(
            HttpFoundation\Request::METHOD_DELETE,
            '/api/movies/'.$movie->getId(),
            [],
            [],
            [
                'HTTP_ACCEPT' => 'application/json',
                'HTTP_AUTHORIZATION' => sprintf('Bearer %s', $this->accessToken),
            ],
        );

        $this->assertEquals(HttpFoundation\Response::HTTP_NO_CONTENT, $this->client->getResponse()->getStatusCode());

        $movieRepository = $this->entityManager->getRepository(Movie::class);
        $movie = $movieRepository->findOneBy(['name' => 'Movie name']);
        $this->assertEmpty($movie);
    }
}
