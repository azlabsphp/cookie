<?php

use Drewlabs\Cookie\CookieInterface;
use Drewlabs\Cookie\Factory;
use PHPUnit\Framework\TestCase;

class CookieFactoryTest extends TestCase
{
    public function test_cookie_factory_create()
    {
        $cookie = Factory::new()->create('sessionId', rand(1000, 100000));
        $this->assertInstanceOf(CookieInterface::class, $cookie);
    }

    public function test_cookie_factory_create_from_string()
    {
        $cookie = Factory::new()->createFromString('sessionId=e8bb43229de9; Expires=Wed, 21 Oct 2015 07:28:00 GMT; Domain=foo.example.com; Path=/; Secure; HttpOnly');
        $this->assertInstanceOf(CookieInterface::class, $cookie);
    }

    public function test_factory_create_cookie_from_string_set_value_for_provided_cookie_components()
    {
        $expiresAt = (new \DateTimeImmutable())->format('U');
        $cookie = Factory::new()->createFromString("sessionId=e8bb43229de9; Expires=$expiresAt; Domain=foo.example.com; Path=/; Secure; HttpOnly");

        $this->assertEquals('sessionId', $cookie->getName());
        $this->assertEquals('e8bb43229de9', $cookie->getValue());
        $this->assertEquals($expiresAt, $cookie->getExpires());
        $this->assertEquals('/', $cookie->getPath());
        $this->assertEquals('foo.example.com', $cookie->getDomain());
        $this->assertTrue($cookie->isSecure());
        $this->assertTrue($cookie->isHttpOnly());
    }
}
