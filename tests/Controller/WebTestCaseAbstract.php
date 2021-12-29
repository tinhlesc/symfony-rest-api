<?php

namespace App\Tests\Controller;

use Exception;
use FOS\OAuthServerBundle\Model\ClientInterface;
use FOS\OAuthServerBundle\Model\ClientManagerInterface;
use OAuth2\OAuth2;
use Symfony\Bundle\FrameworkBundle\KernelBrowser;
use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;
use Symfony\Component\HttpFoundation\Response;

/**
 * Class WebTestCaseAbstract.
 *
 * @author Voycer Development <dev@voycer.com>
 */
abstract class WebTestCaseAbstract extends WebTestCase
{
    /**
     * Default content type.
     */
    protected const DEFAULT_MIME_TYPE = 'application/json';

    /**
     * @var KernelBrowser
     */
    protected $client;

    /**
     * @throws Exception
     */
    protected function setUp(): void
    {
        parent::setUp();

        $this->client = self::createClient();
    }

    protected function assignApiClient(): ClientInterface
    {
        /** @var ClientManagerInterface $clientManager */
        $clientManager = self::$container->get('fos_oauth_server.client_manager.default');

        $client = $clientManager->createClient();
        $client->setAllowedGrantTypes([OAuth2::GRANT_TYPE_CLIENT_CREDENTIALS]);
        $clientManager->updateClient($client);

        return $client;
    }

    protected function getAccessToken(string $clientId, string $clientSecret): string
    {
        $this->client->request(
            'POST',
            '/oauth/v2/token',
            [
                'grant_type' => 'client_credentials',
            ],
            [],
            [
                'HTTP_AUTHORIZATION' => 'Basic '.base64_encode($clientId.':'.$clientSecret),
                'HTTP_ACCEPT' => '*/*',
                'PHP_AUTH_USER' => $clientId,
                'PHP_AUTH_PW' => $clientSecret,
            ]
        );

        if (Response::HTTP_OK === $this->client->getResponse()->getStatusCode()) {
            $content = json_decode($this->client->getResponse()->getContent(), true);
            if (array_key_exists('access_token', $content)) {
                return $content['access_token'];
            }
        }

        return '';
    }
}