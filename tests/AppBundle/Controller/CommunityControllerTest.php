<?php

namespace Tests\AppBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;

class CommunityControllerTest extends WebTestCase
{
  public function testProfile() {
      $client = static::createClient();

      $crawler = $client->request('GET', '/profile/test');
      $this->assertCount(3, $crawler->filter('div'));

      $crawler = $client->request('GET', '/profile/meleadeles');
      $this->assertEquals(200, $client->getResponse()->getStatusCode());
      $this->assertGreaterThan(8, $crawler->filter('div')->count());

      $client = static::createClient(array(), array(
          'PHP_AUTH_USER' => 'test',
          'PHP_AUTH_PW'   => 'test',
      ));
      $crawler = $client->request('GET', '/profile/test');
      var_dump($crawler->filter('div'));
      $this->assertCount(4, $crawler->filter('div'));

      $crawler = $client->request('GET', '/profile/meleadeles');
      $this->assertEquals(200, $client->getResponse()->getStatusCode());
      $this->assertGreaterThan(9, $crawler->filter('div')->count());
  }
}
