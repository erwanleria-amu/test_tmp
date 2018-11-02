<?php

namespace Tests\AppBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;

class SecurityControllerTest extends WebTestCase
{
    public function testRegister(){
        $client = static::createClient();
        $crawler = $client->request('GET', '/register');

        $this->assertCount(12, $crawler->filter('div'));
        $this->assertCount(0,  $crawler->filter('b'));

        $form = $crawler->selectButton('submit')->form();
        /*$form['_username'] = 'testForm';
        $form['_email'] = 'testForm@gmail.com';
        $form['_plainPassword[first]'] = 'testForm';
        $form['_plainPassword[second]'] = 'testForm';
        
        $crawler = $client->submit($form);

        $this->assertCount(1,  $crawler->filter('b'));*/
    }
}
