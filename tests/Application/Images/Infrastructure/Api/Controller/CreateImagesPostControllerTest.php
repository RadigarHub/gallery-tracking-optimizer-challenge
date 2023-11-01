<?php

declare(strict_types=1);

namespace App\Tests\Application\Images\Infrastructure\Api\Controller;

use Symfony\Bundle\FrameworkBundle\KernelBrowser;
use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

class CreateImagesPostControllerTest extends WebTestCase
{
    private const ENDPOINT = '/images';
    private const PAYLOAD = [
        [
            'id' => '29d3cff2-1213-4c62-a684-7df01e5fa493',
            'name' => 'Image Test 1',
            'url' => 'http://test1.com',
            'created_at' => '2022-01-29T19:18:12Z',
        ],
        [
            'id' => 'c3b9c4b1-e335-4c59-85cb-c4bce4b246fd',
            'name' => 'Image Test 2',
            'url' => 'http://test2.com',
            'created_at' => '2021-03-05T00:30:47Z',
        ],
    ];

    private KernelBrowser $client;

    protected function setUp(): void
    {
        parent::setUp();
        $this->client = static::createClient();
        $this->client->setServerParameter('CONTENT_TYPE', 'application/json');
    }

    /** @test */
    public function create_valid_images(): void
    {
        $this->client->request(Request::METHOD_POST, self::ENDPOINT, [], [], [], \json_encode(self::PAYLOAD));

        $response = $this->client->getResponse();
        $responseData = \json_decode($response->getContent(), true, 512, JSON_THROW_ON_ERROR);
        self::assertResponseStatusCodeSame(Response::HTTP_OK);
        self::assertEquals('Images received', $responseData['message']);
    }

    /** @test */
    public function create_duplicated_images(): void
    {
        $this->client->request(
            Request::METHOD_POST,
            self::ENDPOINT,
            [],
            [],
            [],
            \json_encode(array_merge(self::PAYLOAD, [self::PAYLOAD[0]]))
        );

        $response = $this->client->getResponse();
        $responseData = \json_decode($response->getContent(), true, 512, JSON_THROW_ON_ERROR);
        self::assertResponseStatusCodeSame(Response::HTTP_OK);
        self::assertEquals('Images received', $responseData['message']);
    }

    /** @test */
    public function create_images_when_exist(): void
    {
        $this->client->request(Request::METHOD_POST, self::ENDPOINT, [], [], [], \json_encode(self::PAYLOAD));
        $this->client->request(Request::METHOD_POST, self::ENDPOINT, [], [], [], \json_encode(self::PAYLOAD));

        $response = $this->client->getResponse();
        $responseData = \json_decode($response->getContent(), true, 512, JSON_THROW_ON_ERROR);
        self::assertResponseStatusCodeSame(Response::HTTP_OK);
        self::assertEquals('Images received', $responseData['message']);
    }

    /** @test */
    public function create_invalid_images(): void
    {
        $this->client->request(Request::METHOD_POST, self::ENDPOINT, [], [], [], \json_encode(['invalid_json']));

        $response = $this->client->getResponse();
        $responseData = \json_decode($response->getContent(), true, 512, JSON_THROW_ON_ERROR);
        self::assertResponseStatusCodeSame(Response::HTTP_BAD_REQUEST);
        self::assertEquals('The request payload is not well formed', $responseData['message']);
    }

    /** @test */
    public function create_empty_images(): void
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
}
