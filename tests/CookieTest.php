<?php

use Drewlabs\Cookie\Cookie;
use Drewlabs\Cookie\CookieInterface;
use PHPUnit\Framework\TestCase;

class CookieTest extends TestCase
{

    public function test_cookie_constructor()
    {
        $cookie = new Cookie('id', uniqid('cookie').time());
        $this->assertInstanceOf(CookieInterface::class, $cookie);
    }


    public function test_cookie_with_expires_does_not_modify_original_cookie()
    {
        $cookie = new Cookie('id', uniqid('cookie').time());
        $addedTime = (new DateTimeImmutable())->add(new DateInterval('PT3M'));
        $cookie2 = $cookie->withExpires($addedTime);
        $this->assertNotEquals($cookie, $cookie2);
        $this->assertEquals(0, $cookie->getExpires());
        $this->assertNotNull($cookie2->getExpires());
        $this->assertEquals($addedTime->getTimestamp(), $cookie2->getExpires());
    }
}