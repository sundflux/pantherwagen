<?php

namespace App\Tests;

use Symfony\Component\Panther\PantherTestCase;

class TitleTestCase extends PantherTestCase
{
  public function testMyApp()
  {
    $client = static::createPantherClient(['external_base_uri' => 'https://pantherwagen.fi.docker.amazee.io']); // Your app is automatically started using the built-in web server
    $crawler = $client->request('GET', '/');

    $this->assertContains('My Site', $crawler->filter('title')->html()); // You can use any PHPUnit assertion
  }
}
