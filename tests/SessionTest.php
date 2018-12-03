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
        $this->assertSame('SymfonyCon Schedule', $crawler->filter('h1:first-of-type')->text());

        $crawler = $client->clickLink(self::SESSION_TITLE);
        $this->assertSame(self::SESSION_TITLE, $crawler->filter('h1:first-of-type')->text());

        $client->waitFor('#no-comments');
        $this->assertSame('No feedback yet 🙁!', $crawler->filter('#no-comments')->text());

        $client->getMouse()->clickTo('.vue-star-rating-pointer:nth-of-type(5)');
        $crawler = $client->submitForm('Post', [
            'author' => 'Kévin',
            'comment' => 'I hope you\'re enjoying the talk!',
        ]);

        $client->waitFor('#comments li');
        $this->assertContains('I hope you\'re enjoying the talk!', $crawler->filter('#comments li')->text());
        $this->assertEmpty($crawler->filter('#no-comments'));

        $client->takeScreenshot('/tmp/panther.png');
    }
}
