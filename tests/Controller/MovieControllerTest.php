<?php

namespace App\Tests\Controller;

use App\DataFixtures\MovieFixtures;
use Symfony\Component\HttpFoundation;

class MovieControllerTest extends WebTestCaseAbstract
{
    /**
     * @var string
     */
    private $accessToken;

    public function setUp(): void
    {
        parent::setUp();

        $client = $this->assignApiClient();
        $this->accessToken = $this->getAccessToken($client->getPublicId(), $client->getSecret());
    }

    public function testPostMovie()
    {
        $postData = [
            'name' => 'Movie title 1',
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
            json_encode($postData)
        );

        $this->assertEquals(HttpFoundation\Response::HTTP_CREATED, $this->client->getResponse()->getStatusCode());
    }

    public function testGetMovie()
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
}
