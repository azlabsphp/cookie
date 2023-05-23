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

    public function test_cookie_with_value_is_immutable()
    {
        $cookie = new Cookie('id', uniqid('cookie').time());

        $cookie2 = $cookie->withValue(uniqid('cookie2'.time()));

        $this->assertNotEquals($cookie->getValue(), $cookie2->getValue());
    }

    public function test_cookie_with_domain_is_immutable()
    {
        $cookie = new Cookie('id', uniqid('cookie').time());
        $cookie2 = $cookie->withDomain('www.azlabs.xyz');

        $this->assertNull($cookie->getDomain());
        $this->assertEquals('www.azlabs.xyz', $cookie2->getDomain());
        $this->assertNotEquals($cookie->getDomain(), $cookie2->getDomain());

    }

    public function test_cookie_with_http_only_is_immutable()
    {
        $cookie = new Cookie('id', uniqid('cookie').time(), null, null, '/', false, false);
        $cookie2 = $cookie->withHttpOnly();
        $this->assertFalse($cookie->isHttpOnly());
        $this->assertTrue($cookie2->isHttpOnly());
        $this->assertNotEquals($cookie->isHttpOnly(), $cookie2->isHttpOnly());

    }

    public function test_cookie_with_same_site_is_immutable()
    {
        $cookie = new Cookie('id', uniqid('cookie').time(), null, null, '/', false, false);
        $cookie2 = $cookie->withSameSite(Cookie::SAME_SITE_STRICT);
        $this->assertEquals(Cookie::SAME_SITE_LAX, $cookie->getSameSite());
        $this->assertEquals(Cookie::SAME_SITE_STRICT, $cookie2->getSameSite());
        $this->assertNotEquals($cookie->getSameSite(), $cookie2->getSameSite());
    }

    public function test_cookie_with_path_is_immutable()
    {
        $cookie = new Cookie('id', uniqid('cookie').time(), null, null, '/', false, false);
        $cookie2 = $cookie->withPath('/auth');
        $this->assertEquals('/', $cookie->getPath());
        $this->assertEquals('/auth', $cookie2->getPath());
        $this->assertNotEquals($cookie->getPath(), $cookie2->getPath());
    }

    public function test_cookie_with_same_site_throws_invalid_argument_exception_for_invalid_values()
    {
        $this->expectException(InvalidArgumentException::class);
        $cookie = new Cookie('id', uniqid('cookie').time(), null, null, '/', false, false);
        $cookie->withSameSite('production');
    }
}