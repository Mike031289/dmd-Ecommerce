<?php

namespace App\Tests\Controller\Account;

use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;

final class OrderControllerTest extends WebTestCase
{
    public function testIndex(): void
    {
        $client = static::createClient();
        $client->request('GET', '/account/order');

        self::assertResponseIsSuccessful();
    }
}
