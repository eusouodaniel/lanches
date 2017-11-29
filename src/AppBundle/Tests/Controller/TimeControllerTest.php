<?php

namespace AppBundle\Tests\Controller;

use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;

class TimeControllerTest extends WebTestCase
{
    public function testAjaxtimeavailable()
    {
        $client = static::createClient();

        $crawler = $client->request('GET', '/ajaxTimeAvailable');
    }

}
