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
        $client->request($method, self::FATHER_ENDPOINT, [
            'json' => [
                'name' => 'father2',
                'sons' => []
            ],
        ]);
        self::assertResponseIsSuccessful();
        $sons = static::getContainer()->get(SonRepository::class)->findBy(['father' => self::FATHER_ID]);
        self::assertCount(0, $sons);
        foreach ($sons as $index => $son) {
            if ($index > 1) {
                return;
            }
            self::assertEquals($index + 1, $son->getId());
        }
    }

    public function testAdd(): void
    {
        $method = Request::METHOD_PUT;
        $client = self::createClient();
        $client->request($method, self::FATHER_ENDPOINT, [
            'json' => [
                'name' => 'father2',
                'sons' => [
                    [
                        'name' => 'son1',
                        'age' => 10,
                    ],
                    [
                        'name' => 'son2',
                        'age' => 20,
                    ],
                    [
                        'name' => 'son3',
                        'age' => 30,
                    ],
                ]
            ],
        ]);
        self::assertResponseIsSuccessful();
        $sons = static::getContainer()->get(SonRepository::class)->findBy(['father' => self::FATHER_ID]);
        self::assertCount(3, $sons);
        foreach ($sons as $index => $son) {
            if ($index > 1) {
                return;
            }
            self::assertEquals($index + 1, $son->getId());
        }
    }

    public function testAdd2TimesDuplicate(): void
    {
        $method = Request::METHOD_PUT;
        $client = self::createClient();
        $client->request($method, self::FATHER_ENDPOINT, [
            'json' => [
                'name' => 'father2',
                'sons' => [
                    [
                        'name' => 'son1',
                        'age' => 10,
                    ],
                    [
                        'name' => 'son2',
                        'age' => 20,
                    ],
                    [
                        'name' => 'son1',
                        'age' => 30,
                    ],
                    [
                        'name' => 'son1',
                        'age' => 40,
                    ],
                ]
            ],
        ]);
        self::assertResponseIsSuccessful();
        $sons = static::getContainer()->get(SonRepository::class)->findBy(['father' => self::FATHER_ID]);
        self::assertCount(2, $sons);
        foreach ($sons as $index => $son) {
            if ($index > 1) {
                return;
            }
            self::assertEquals($index + 1, $son->getId());
        }
    }

    public function testAddAndRemoveAlreadyExists(): void
    {
        $method = Request::METHOD_PUT;
        $client = self::createClient();
        $client->request($method, self::FATHER_ENDPOINT, [
            'json' => [
                'name' => 'father2',
                'sons' => [
                    [
                        'name' => 'son1',
                        'age' => 10,
                    ],
                    [
                        'name' => 'son3',
                        'age' => 30,
                    ],
                ]
            ],
        ]);
        self::assertResponseIsSuccessful();
        $sons = static::getContainer()->get(SonRepository::class)->findBy(['father' => self::FATHER_ID]);
        self::assertCount(2, $sons);
        foreach ($sons as $index => $son) {
            if ($index > 0) {
                return;
            }
            self::assertEquals($index + 1, $son->getId());
        }
    }

    public function testNoChanges(): void
    {
        $method = Request::METHOD_PUT;
        $client = self::createClient();
        $client->request($method, self::FATHER_ENDPOINT, [
            'json' => [
                'name' => 'father2',
                'sons' => [
                    [
                        'name' => 'son1',
                        'age' => 10,
                    ],
                    [
                        'name' => 'son2',
                        'age' => 20,
                    ],
                ]
            ],
        ]);
        self::assertResponseIsSuccessful();
        $sons = static::getContainer()->get(SonRepository::class)->findBy(['father' => self::FATHER_ID]);
        self::assertCount(2, $sons);
        foreach ($sons as $index => $son) {
            if ($index > 1) {
                return;
            }
            self::assertEquals($index + 1, $son->getId());
        }
    }
}
