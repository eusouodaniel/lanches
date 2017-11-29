<?php

namespace AppBundle\Tests\Controller;

use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;

class WorkControllerTest extends WebTestCase
{
    public function testWorkwithus()
    {
        $client = static::createClient();

        $crawler = $client->request('GET', '/trabalhe-conosco');
    }

}
