<?php

namespace App\Tests;

use Hautelook\AliceBundle\PhpUnit\ReloadDatabaseTrait;
use Symfony\Component\Panther\PantherTestCase;

class SessionTest extends PantherTestCase
{
    private const SESSION_TITLE = 'Integrate (Vue)JS components in a Symfony app, add E2E tests with Panther';

    use ReloadDatabaseTrait; // From AliceBundle, reloads the fixtures before every test

    public function testComment()
    {
        $client = static::createPantherClient();
        $crawler = $client->request('GET', '/');
        $this->assertSame('Schedule', $crawler->filter('h1:first-of-type')->text());

        $crawler = $client->clickLink(self::SESSION_TITLE);
        $this->assertSame(self::SESSION_TITLE, $crawler->filter('article h1:first-of-type')->text());

        $client->waitFor('#no-comments');
        $this->assertSame('No feedback yet !', $crawler->filter('#no-comments')->text());

        $client->getMouse()->clickTo('.vue-star-rating-pointer:nth-of-type(5)');
        $crawler = $client->submitForm('Post', [
            'author' => 'Kévin',
            'comment' => 'I hope you\'re enjoying the talk!',
        ]);

        $client->waitFor('#comments article p');
        $this->assertContains('I hope you\'re enjoying the talk!', $crawler->filter('#comments article p')->text());
        $this->assertEmpty($crawler->filter('#no-comments'));

        $client->takeScreenshot('/tmp/panther.png');
    }
}
