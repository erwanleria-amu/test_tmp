<?php

namespace Tests\AppBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;

class DefaultControllerTest extends WebTestCase
{
    public function testApp()
    {
        $client = static::createClient();

        $crawler = $client->request('GET', '/app');

        $this->assertEquals(200, $client->getResponse()->getStatusCode());
        $this->assertCount(5, $crawler->filter('div'));

        $client = static::createClient(array(), array(
            'PHP_AUTH_USER' => 'test',
            'PHP_AUTH_PW'   => 'test',
        ));

        $crawler = $client->request('GET', '/app');
        $this->assertCount(7, $crawler->filter('div'));
    }

    public function testAppCoordinates()
    {
        $client = static::createClient();

        $crawler = $client->request('GET', '/app', array(
            'lat' => 17,
            'lon' => 45
        ));

        $this->assertEquals(200, $client->getResponse()->getStatusCode());
        $this->assertCount(5, $crawler->filter('div'));

        $client = static::createClient(array(), array(
            'PHP_AUTH_USER' => 'test',
            'PHP_AUTH_PW'   => 'test',
        ));

        $crawler = $client->request('GET', '/app', array(
            'lat' => 17,
            'lon' => 45
        ));
        $this->assertCount(7, $crawler->filter('div'));
    }
}
