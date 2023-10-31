<?php

declare(strict_types=1);

namespace App\Tests\Application\Images\Infrastructure\Api\Controller;

use Symfony\Bundle\FrameworkBundle\KernelBrowser;
use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

class FindImagesGetControllerTest extends WebTestCase
{
    private const OLDEST_IMAGE_ID = '29d3cff2-1213-4c62-a684-7df01e5fa493';
    private const HEAVIEST_IMAGE_ID = 'c3b9c4b1-e335-4c59-85cb-c4bce4b246fd';
    private const MEDIUM_IMAGE_ID = '490cee93-fced-40cb-9061-6a776b2f866b';
    private const NEWEST_IMAGE_ID = '6319c508-2a1f-479c-beae-d77a64e1552c';

    private const ENDPOINT = '/images';

    private KernelBrowser $client;

    protected function setUp(): void
    {
        parent::setUp();
        $this->client = static::createClient();
        $this->client->setServerParameter('CONTENT_TYPE', 'application/json');
    }

    /** @test */
    public function find_images_sorted_in_correct_order(): void
    {
        $this->sendRequestToCreateImages();
        $this->sendRequestToCreateEvents();

        $this->client->request(Request::METHOD_GET, self::ENDPOINT);

        $response = $this->client->getResponse();
        $responseData = \json_decode($response->getContent(), true, 512, JSON_THROW_ON_ERROR);
        self::assertResponseStatusCodeSame(Response::HTTP_OK);
        self::assertEquals(self::HEAVIEST_IMAGE_ID, $responseData[0]['id']);
        self::assertEquals(0.7, $responseData[0]['weight']);
        self::assertEquals(self::MEDIUM_IMAGE_ID, $responseData[1]['id']);
        self::assertEquals(0.6, $responseData[1]['weight']);
        self::assertEquals(self::NEWEST_IMAGE_ID, $responseData[2]['id']);
        self::assertEquals(0, $responseData[2]['weight']);
        self::assertEquals(self::OLDEST_IMAGE_ID, $responseData[3]['id']);
        self::assertEquals(0, $responseData[3]['weight']);
    }

    /** @test */
    public function find_without_images(): void
    {
        $this->client->request(Request::METHOD_GET, self::ENDPOINT);

        $response = $this->client->getResponse();
        $responseData = \json_decode($response->getContent(), true, 512, JSON_THROW_ON_ERROR);
        self::assertResponseStatusCodeSame(Response::HTTP_NOT_FOUND);
        self::assertEquals('They are not registered', $responseData['message']);
    }

    private function sendRequestToCreateImages(): void
    {
        $ThreeDaysAgo = (new \DateTimeImmutable('3 days ago', new \DateTimeZone('UTC')))->format('Y-m-d\TH:i:s\Z');
        $twoDaysAgo = (new \DateTimeImmutable('2 days ago', new \DateTimeZone('UTC')))->format('Y-m-d\TH:i:s\Z');
        $yesterday = (new \DateTimeImmutable('yesterday', new \DateTimeZone('UTC')))->format('Y-m-d\TH:i:s\Z');
        $this->client->request(
            Request::METHOD_POST, '/images', [], [],
            [],
            \json_encode([
                [
                    'id' => self::OLDEST_IMAGE_ID,
                    'name' => 'Image Test - OLDEST',
                    'url' => 'http://testoldest.com',
                    'created_at' => $ThreeDaysAgo,
                ],
                [
                    'id' => self::HEAVIEST_IMAGE_ID,
                    'name' => 'Image Test - HEAVIEST',
                    'url' => 'http://testheaviest.com',
                    'created_at' => $twoDaysAgo,
                ],
                [
                    'id' => self::MEDIUM_IMAGE_ID,
                    'name' => 'Image Test - MEDIUM',
                    'url' => 'http://testmedium.com',
                    'created_at' => $twoDaysAgo,
                ],
                [
                    'id' => self::NEWEST_IMAGE_ID,
                    'name' => 'Image Test - NEWEST',
                    'url' => 'http://testnewest.com',
                    'created_at' => $yesterday,
                ],
            ], JSON_THROW_ON_ERROR)
        );
    }

    private function sendRequestToCreateEvents(): void
    {
        $yesterday = (new \DateTimeImmutable('yesterday', new \DateTimeZone('UTC')))->format('Y-m-d\TH:i:s\Z');
        $this->client->request(
            Request::METHOD_POST,
            \sprintf('/images/%s/events', self::MEDIUM_IMAGE_ID),
            [],
            [],
            [],
            \json_encode([
                'eventType' => 'view',
                'timestamp' => $yesterday,
            ], JSON_THROW_ON_ERROR)
        );

        $this->client->request(
            Request::METHOD_POST,
            \sprintf('/images/%s/events', self::HEAVIEST_IMAGE_ID),
            [],
            [],
            [],
            \json_encode([
                'eventType' => 'click',
                'timestamp' => $yesterday,
            ], JSON_THROW_ON_ERROR)
        );

        $this->client->request(
            Request::METHOD_POST,
            \sprintf('/images/%s/events', self::MEDIUM_IMAGE_ID),
            [],
            [],
            [],
            \json_encode([
                'eventType' => 'view',
                'timestamp' => $yesterday,
            ], JSON_THROW_ON_ERROR)
        );
    }
}
