<?php

declare(strict_types=1);

namespace Drupal\dummycontent\Commands;

use Drush\Commands\DrushCommands;
use Drupal\Core\Entity\EntityTypeManagerInterface;
use GuzzleHttp\ClientInterface;

/**
 * Class DummySeeder
 *
 * @package Drupal\dummycontent\Commands
 */
class DummySeeder extends DrushCommands {

  /**
   * Dummy posts amount
   */
  const CREATE_AMOUNT = 10;

  /**
   * Owner account
   */
  const OWNER_ACCOUNT_ID = 1;

  /**
   * Baconipsum random content amount
   */
  const BACON_COUNT = 5;

  /**
   * Bacon ipsum service for titles.
   */
  const BACON_IPSUM_TITLE_SERVICE = 'https://baconipsum.com/api/?type=all-meat&sentences=1&start-with-lorem=1';

  /**
   * Bacon ipsum service for paragraph content.
   */
  const BACON_IPSUM_CONTENT_SERVICE = 'https://baconipsum.com/api/?type=all-meat&paras=3&start-with-lorem=1&format=html';

  /**
   * @var \GuzzleHttp\ClientInterface
   */
  protected $httpClient;

  /**
   * The entity type manager.
   *
   * @var \Drupal\Core\Entity\EntityTypeManagerInterface
   */
  protected $entityTypeManager;

  /**
   * Cache array for bacon ipsum titles.
   *
   * @var array
   */
  private $titles = [];

  /**
   * Cache array for bacon ipsum paragraphs.
   *
   * @var array
   */
  private $paragraphs = [];

  /**
   * DummySeeder constructor.
   *
   * @param \Drupal\Core\Entity\EntityTypeManagerInterface $entityTypeManager
   * @param \GuzzleHttp\ClientInterface $http_client
   */
  public function __construct(
    EntityTypeManagerInterface $entityTypeManager,
    ClientInterface $http_client
  ) {
    $this->entityTypeManager = $entityTypeManager;
    $this->httpClient = $http_client;
  }

  /**
   * Get 10 random titles from bacon ipsum and return random title.
   *
   * @return string
   */
  private function getTitle() {
    if (empty($this->titles)) {
      // Get X random titles for Bacon ipsum.
      for ($i = 0; $i <= self::BACON_COUNT; $i++) {
        $request = $this->httpClient->get(self::BACON_IPSUM_TITLE_SERVICE);
        $response = $request->getBody();
        $sentence = json_decode((string) $response);

        // Some sentences are too long so randomize them a bit.
        $words = str_word_count($sentence[0], 1);
        shuffle($words);
        $randomWords = array_slice($words, 0, rand(3, 10));
        $this->titles[] = ucfirst(strtolower(implode(" ", $randomWords)));
      }
    }

    return (string) $this->titles[rand(1, self::BACON_COUNT)];
  }

  /**
   * Get 5 random paragraphs from bacon ipsum and return random paragraph.
   *
   * @return string
   */
  private function getParagraph() {
    if (empty($this->paragraphs)) {
      // Get X random paragraphs for Bacon ipsum.
      for ($i = 0; $i <= self::BACON_COUNT; $i++) {
        $request = $this->httpClient->get(self::BACON_IPSUM_CONTENT_SERVICE);
        $this->paragraphs[] = (string) $request->getBody();
      }
    }

    return (string) $this->paragraphs[rand(1, self::BACON_COUNT)];
  }

  /**
   * Get user id who will own the content.
   *
   * @return int
   *
   * @todo Possibly randomize this, but admin(1) works fine for testing.
   */
  private function getUserId() {
    return self::OWNER_ACCOUNT_ID;
  }

  /**
   * Create lorem ipsum article.
   *
   * @return \Drupal\Core\Entity\EntityInterface
   *
   * @throws \Drupal\Component\Plugin\Exception\InvalidPluginDefinitionException
   * @throws \Drupal\Component\Plugin\Exception\PluginNotFoundException
   * @throws \Drupal\Core\Entity\EntityStorageException
   */
  private function createArticle() {
    // Generate article.
    $article = $this->entityTypeManager->getStorage('node')
      ->create([
        'type' => 'article',
        'title' => $this->getTitle(),
        'uid' => $this->getUserId(),
        'body' => [
          'value' => $this->getParagraph(),
          'format' => 'full_html',
        ],
      ]);
    $article->save();
    return $article;
  }

  /**
   * Generate dummy articles
   *
   * @command dummycontent:generate
   *
   * @validate-module-enabled dummycontent
   * @aliases
   */
  public function generate() {
    for ($i = 0; $i <= self::CREATE_AMOUNT; $i++) {
      $a = $this->createArticle();
      echo "Created dummy article: " . $a->getTitle() . "\n";
    }
  }

}
