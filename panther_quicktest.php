<?php

require __DIR__.'/vendor/autoload.php'; // Composer's autoloader

$client = \Symfony\Component\Panther\Client::createChromeClient();
$crawler = $client->request('GET', 'http://pantherwagen.fi.docker.amazee.io/');

$link = $crawler->selectLink('My Site')->link();
$crawler = $client->click($link);

// Wait for an element to be rendered
$client->waitFor('.field');

echo $crawler->filter('.field')->text();
$client->takeScreenshot('screen.png'); // Yeah, screenshot!
