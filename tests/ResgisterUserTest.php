<?php

namespace App\Tests;

use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;

class ResgisterUserTest extends WebTestCase
{
    public function testSomething(): void
    {
        $client = static::createClient();
        $crawler = $client->request('GET', '/inscription');

        $client->submitForm('register_user[submit]', [
            'register_user[email]'=> 'julie@gmail.com',
            'register_user[plainPassword][first]'=> '123456',
            'register_user[plainPassword][second]'=> '123456',
            'register_user[firstname]'=> 'Julie',
            'register_user[lastname]'=> 'Doe'
        ]);
     

        $this->assertResponseRedirects('/connexion');
        $client->followRedirect();
        // $this->assertResponseIsSuccessful('Hello Mike');
        $this->assertSelectorExists('div', 'Votre compte est correctement créé, veuillez vous connecter');
        // $this->assertSelectorTextContains('', 'Votre compte est correctement créé, veuillez vous connecter');
    }
}
