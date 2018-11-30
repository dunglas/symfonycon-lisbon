<?php

namespace App\Tests;

use Hautelook\AliceBundle\PhpUnit\ReloadDatabaseTrait;
use Symfony\Component\Panther\PantherTestCase;

class SessionTest extends PantherTestCase
{
    private const SESSION_TITLE = 'Integrate (Vue)JS components in a Symfony app, add E2E tests with Panther';

    use ReloadDatabaseTrait;

    public function testGiveFeedback()
    {
        $client = static::createPantherClient();
        $crawler = $client->request('GET', '/');
        $this->assertSame('SymfonyCon Schedule', $crawler->filter('h1:first-of-type')->text());

        $crawler = $client->clickLink(self::SESSION_TITLE);
        $this->assertSame(self::SESSION_TITLE, $crawler->filter('h1:first-of-type')->text());

        $client->waitFor('#feedback div p');
        $this->assertSame('No feedback yet 🙁!', $crawler->filter('#feedback div p')->text());
    }
}

