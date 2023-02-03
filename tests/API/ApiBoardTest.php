<?php

namespace App\Tests\API;

use ApiPlatform\Symfony\Bundle\Test\ApiTestCase;
use App\Entity\Board;

class ApiBoardTest extends ApiTestCase
{
    public function testGetCollection(): void
    {
        $response = static::createClient()->request('GET', '/api/boards');
        $this->assertResponseIsSuccessful();

        $this->assertResponseHeaderSame('content-type', 'application/ld+json; charset=utf-8');

        $this->assertJsonContains([
            '@context' => '/api/contexts/Board',
            '@id' => '/api/boards',
            '@type' => 'hydra:Collection',
            'hydra:totalItems' => 4,
        ]);
    }

    public function testCreateBoard(): void
    {
        $response = static::createClient()->request('POST', 'api/boards', ['json' => [
            'name' => 'test board',
            'brand' => 'test brand',
            'description' => 'test descirption',
            'size' => '5\'11',
            'volume' => '45L',
            'dimension' => 'x 19\' 3/4" x 2\' 7/16"',
            'Price' => 2000,
            'status' => 'SOLDE',
        ]]);

        $this->assertResponseStatusCodeSame(201);
        $this->assertResponseHeaderSame('content-type', 'application/ld+json; charset=utf-8');
        $this->assertJsonContains([
            '@context' => '/api/contexts/Board',
            '@type' => 'Board',
            'name' => 'test board',
            'brand' => 'test brand',
            'description' => 'test descirption',
            'size' => '5\'11',
            'volume' => '45L',
            'dimension' => 'x 19\' 3/4" x 2\' 7/16"',
            'Price' => 2000,
            'status' => 'SOLDE',
        ]);
        $this->assertMatchesRegularExpression('~^/api/boards/\d+$~', $response->toArray()['@id']);
    }

    // public function testCreateInvalidBoard(): void
    // {
    //     static::createClient()->request('POST', 'api/boards', ['json' => [
    //         'Price' => -2000,
    //     ]]);

    //     $this->assertResponseStatusCodeSame(422);
    //     $this->assertResponseHeaderSame('content-type', 'application/ld+json; charset=utf-8');

    //     $this->assertJsonContains([
    //         '@context' => '/api/contexts/ConstraintViolationList',
    //         '@type' => 'ConstraintViolationList',
    //         'hydra:title' => 'An error occurred',
    //         'hydra:description' => 'name: This value should not be blank.
    //             Price: This value should be positive.',
    //         'violations' => [
    //             0 =>
    //             array(
    //                 'propertyPath' => 'name',
    //                 'message' => 'This value should not be blank.',
    //                 'code' => 'c1051bb4-d103-4f74-8988-acbcafc7fdc3',
    //             ),
    //         ]
    //     ]);
    // }

    public function testUpdateBoard(): void
    {
        $client = static::createClient();
        $board = $this->findIriBy(Board::class, ['brand' => 'Torq']);

        $client->request('PUT', $board, ['json' => [
            'name' => 'The don update',
        ]]);

        $this->assertResponseIsSuccessful();
        $this->assertJsonContains([
            '@id' => $board,
            'name' => 'The don update',
        ]);
    }

    public function testDeleteBoard(): void
    {
        $client = static::createClient();
        $iri = $this->findIriBy(Board::class, ['brand' => 'Al Merrick']);

        $client->request('DELETE', $iri);

        $this->assertResponseStatusCodeSame(204);
        $this->assertNull(
            // Through the container, you can access all your services from the tests, including the ORM, the mailer, remote API clients...
            static::getContainer()->get('doctrine')->getRepository(Board::class)->findOneBy(['brand' => 'Al Merrick'])
        );
    }
}
