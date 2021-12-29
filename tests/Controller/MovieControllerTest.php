<?php

namespace App\Tests\Controller;

use Symfony\Component\HttpFoundation;

class MovieControllerTest extends WebTestCaseAbstract
{
    public function testPostMovie()
    {
        $client = $this->assignApiClient();
        $accessToken = $this->getAccessToken($client->getPublicId(), $client->getSecret());

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
                'HTTP_AUTHORIZATION' => sprintf('Bearer %s', $accessToken),
            ],
            json_encode($postData)
        );

        $this->assertEquals(HttpFoundation\Response::HTTP_CREATED, $this->client->getResponse()->getStatusCode());
        $data = json_decode($this->client->getResponse()->getContent(), true);
        $this->assertIsArray($data);
    }

    /**
     * @depends testPostMovie
     */
    public function testGetMovie()
    {
        $client = $this->assignApiClient();
        $accessToken = $this->getAccessToken($client->getPublicId(), $client->getSecret());

        $this->client->request(
            HttpFoundation\Request::METHOD_GET,
            '/api/movies',
            [],
            [],
            [
                'HTTP_ACCEPT' => 'application/json',
                'HTTP_AUTHORIZATION' => sprintf('Bearer %s', $accessToken),
            ]
        );

        $this->assertEquals(HttpFoundation\Response::HTTP_OK, $this->client->getResponse()->getStatusCode());
        $data = json_decode($this->client->getResponse()->getContent(), true);
        echo count($data);
        $this->assertIsArray($data);
    }
}
