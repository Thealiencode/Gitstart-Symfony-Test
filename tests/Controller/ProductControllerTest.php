<?php

namespace App\Tests\Controller;

use App\Tests\Factory\ProductFactory;
use Symfony\Component\HttpFoundation\Response;

class ProductControllerTest extends BaseTestCase
{
    public function testIndex(): void
    {
        $this->client->request('GET', '/api/products');


        $this->assertEquals(Response::HTTP_OK, $this->client->getResponse()->getStatusCode());
        $responseJson = (string)$this->client->getResponse()->getContent();
        $this->assertJson($responseJson);

        /** @var array{data: array, status: string, "message" : "string"} $responseContent */
        $responseContent = json_decode($responseJson, true);

        $this->assertEquals('success', $responseContent['status']);
        $this->assertIsArray($responseContent['data']);
    }

    public function testShow(): void
    {
        $this->client->request('GET', '/api/products/1');


        $this->assertEquals(Response::HTTP_OK, $this->client->getResponse()->getStatusCode());
        $responseJson = (string)$this->client->getResponse()->getContent();
        $this->assertJson($responseJson);

        /** @var array{data: array, status: string, "message" : "string"} $responseContent */
        $responseContent = json_decode($responseJson, true);

        $this->assertEquals('success', $responseContent['status']);
        $this->assertIsArray($responseContent['data']);
    }

    public function testCreate(): void
    {
        $this->client->request(
            'POST',
            'api/products',
            [],
            [],
            ['CONTENT_TYPE' => 'application/json'],
            (string)json_encode([
                'name' => 'Test Product',
                'description' => 'This is a test product',
                'price' => 9.99
            ])
        );

        $this->assertEquals(Response::HTTP_CREATED, $this->client->getResponse()->getStatusCode());
        $responseJson = (string)$this->client->getResponse()->getContent();
        $this->assertJson($responseJson);

        /** @var array{data: array, status: string, "message" : "string"} $responseContent */
        $responseContent = json_decode($responseJson, true);
        $this->assertEquals('Product Created Successfully', $responseContent['message']);
        $this->assertEquals('success', $responseContent['status']);
    }

    public function testUpdate(): void
    {
        $this->client->request(
            'PUT',
            'api/products/1',
            [],
            [],
            ['CONTENT_TYPE' => 'application/json'],
            (string)json_encode([
                'name' => 'UpdateTest Product',
                'description' => 'This is a test product update',
                'price' => 9.99
            ])
        );

        $this->assertEquals(200, $this->client->getResponse()->getStatusCode());
        $responseJson = (string)$this->client->getResponse()->getContent();
        $this->assertJson($responseJson);

        /** @var array{data: array, status: string, "message" : "string"} $responseContent */
        $responseContent = json_decode($responseJson, true);
        $this->assertEquals("Product Update Successfully", $responseContent['message']);
        $this->assertEquals('success', $responseContent['status']);
    }

    public function testDelete(): void
    {
        $this->client->request(
            'DELETE',
            'api/products/1',
            [],
            [],
            ['CONTENT_TYPE' => 'application/json'],
        );

        $this->assertEquals(200, $this->client->getResponse()->getStatusCode());
        $responseJson = (string)$this->client->getResponse()->getContent();
        $this->assertJson($responseJson);

        /** @var array{data: array, status: string, "message" : "string"} $responseContent */
        $responseContent = json_decode($responseJson, true);

        $this->assertEquals('Product deleted', $responseContent['message']);
        $this->assertEquals('success', $responseContent['status']);
    }
}
