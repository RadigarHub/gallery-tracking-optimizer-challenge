<?php

declare(strict_types=1);

namespace App\Tests\Application\Images\Infrastructure\Api\Controller;

use Symfony\Bundle\FrameworkBundle\KernelBrowser;
use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

class CreateEventPostControllerTest extends WebTestCase
{
    private const ENDPOINT = '/images/29d3cff2-1213-4c62-a684-7df01e5fa493/events';
    private const PAYLOAD = [
        'eventType' => 'view',
        'timestamp' => "2019-08-24T14:15:22Z",
    ];

    private KernelBrowser $client;

    protected function setUp(): void
    {
        parent::setUp();
        $this->client = static::createClient();
        $this->client->setServerParameter('CONTENT_TYPE', 'application/json');
    }

    /** @test */
    public function create_valid_event(): void
    {
        $this->sendRequestToCreateImages();

        $this->client->request(
            Request::METHOD_POST,
            self::ENDPOINT,
            [],
            [],
            [],
            \json_encode(self::PAYLOAD)
        );

        self::assertResponseStatusCodeSame(Response::HTTP_NO_CONTENT);
    }

    /** @test */
    public function create_valid_event_for_non_existing_image(): void
    {
        $this->client->request(
            Request::METHOD_POST,
            self::ENDPOINT,
            [],
            [],
            [],
            \json_encode(self::PAYLOAD)
        );

        $response = $this->client->getResponse();
        $responseData = \json_decode($response->getContent(), true, 512, JSON_THROW_ON_ERROR);
        self::assertResponseStatusCodeSame(Response::HTTP_NOT_FOUND);
        self::assertEquals('There are no image with the specified ID', $responseData['message']);
    }

    /** @test */
    public function create_invalid_event(): void
    {
        $this->client->request(Request::METHOD_POST, self::ENDPOINT, [], [], [], \json_encode(['invalid_json']));

        $response = $this->client->getResponse();
        $responseData = \json_decode($response->getContent(), true, 512, JSON_THROW_ON_ERROR);
        self::assertResponseStatusCodeSame(Response::HTTP_BAD_REQUEST);
        self::assertEquals('The request payload is not well formed', $responseData['message']);
    }

    /** @test */
    public function create_empty_event(): void
    {
        $this->client->request(Request::METHOD_POST, self::ENDPOINT, [], [], [], \json_encode([]));

        $response = $this->client->getResponse();
        $responseData = \json_decode($response->getContent(), true, 512, JSON_THROW_ON_ERROR);
        self::assertResponseStatusCodeSame(Response::HTTP_BAD_REQUEST);
        self::assertEquals('The request payload is not well formed', $responseData['message']);
    }

    /** @test */
    public function create_invalid_content_type(): void
    {
        $this->client->setServerParameter('CONTENT_TYPE', '');
        $this->client->request(Request::METHOD_POST, self::ENDPOINT, [], [], [], \json_encode(self::PAYLOAD));

        $response = $this->client->getResponse();
        $responseData = \json_decode($response->getContent(), true, 512, JSON_THROW_ON_ERROR);
        self::assertResponseStatusCodeSame(Response::HTTP_BAD_REQUEST);
        self::assertEquals('[application/json] is the only Content-Type allowed', $responseData['message']);
    }

    private function sendRequestToCreateImages(): void
    {
        $this->client->request(Request::METHOD_POST, '/images', [], [], [],
            \json_encode([
                [
                    'id' => '29d3cff2-1213-4c62-a684-7df01e5fa493',
                    'name' => 'Image Test 1',
                    'url' => 'http://test1.com',
                    'created_at' => (new \DateTimeImmutable('yesterday', new \DateTimeZone('UTC')))->format(
                        'Y-m-d\TH:i:s\Z'
                    ),
                ],
            ], JSON_THROW_ON_ERROR));
    }
}
