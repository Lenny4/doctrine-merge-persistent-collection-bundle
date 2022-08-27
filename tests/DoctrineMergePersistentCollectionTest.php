<?php

namespace Lenny4\DoctrineMergePersistentCollectionBundle\Tests;

use ApiPlatform\Core\Bridge\Symfony\Bundle\Test\ApiTestCase;
use Lenny4\DoctrineMergePersistentCollectionBundle\Repository\SonRepository;
use Symfony\Component\HttpFoundation\Request;

class DoctrineMergePersistentCollectionTest extends ApiTestCase
{
    private const FATHER_ID = 1;
    private const FATHER_ENDPOINT = '/api/fathers/' . self::FATHER_ID;

    public function testEmpty(): void
    {
        $method = Request::METHOD_PUT;
        $client = self::createClient();
        $response = $client->request($method, self::FATHER_ENDPOINT, [
            'json' => [
                'name' => 'father2',
                'sons' => []
            ],
        ]);
        self::assertResponseIsSuccessful();
        $sonRepository = static::getContainer()->get(SonRepository::class);
        self::assertCount(0, $sonRepository->findBy(['father' => self::FATHER_ID]));
    }
}
