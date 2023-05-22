<?php

declare(strict_types=1);

/*
 * This file is part of the drewlabs namespace.
 *
 * (c) Sidoine Azandrew <azandrewdevelopper@gmail.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Drewlabs\Cookie;

use DateTimeInterface;

/**
 * Value object representing a cookie.
 *
 * Instances of this interface are considered immutable; all methods that might
 * change state MUST be implemented such that they retain the internal state of
 * the current instance and return an instance that contains the changed state.
 *
 * @see https://tools.ietf.org/html/rfc6265#section-4.1
 * @see https://developer.mozilla.org/en-US/docs/Web/HTTP/Cookies
 * @see https://developer.mozilla.org/en-US/docs/Web/HTTP/Headers/Set-Cookie
 */
interface CookieInterface
{
    /**
     * Returns the cookie as a string representation.
     */
    public function __toString(): string;

    /**
     * Gets the name of the cookie.
     */
    public function getName(): string;

    /**
     * Gets the value of the cookie.
     */
    public function getValue(): string;

    /**
     * Returns an instance with the specified value.
     *
     * This method MUST retain the state of the current instance,
     * and return a new instance that contains the specified value.
     *
     * @return static
     */
    public function withValue(string $value): self;

    /**
     * Gets the max-age attribute.
     */
    public function getMaxAge(): int;

    /**
     * Gets the time the cookie expires.
     */
    public function getExpires(): int;

    /**
     * Whether this cookie is expired.
     */
    public function isExpired(): bool;

    /**
     * Returns an instance that will expire immediately.
     *
     * This method MUST retain the state of the current instance,
     * and return a new instance that contains the specified expires.
     *
     * @return static
     */
    public function expire(): self;

    /**
     * Returns an instance with the specified expires.
     *
     * This method MUST retain the state of the current instance,
     * and return a new instance that contains the specified expires.
     *
     * The `$expire` value can be an `DateTimeInterface` instance,
     * a string representation of a date, or a integer Unix timestamp, or `null`.
     *
     * If the `$expire` was not specified or has an empty value,
     * the cookie MUST be converted to a session cookie,
     * which will expire as soon as the browser is closed.
     *
     * @param \DateTimeInterface|int|string|null $expire
     *
     * @return static
     */
    public function withExpires($expire = null): self;

    /**
     * Gets the domain of the cookie.
     */
    public function getDomain(): ?string;

    /**
     * Returns an instance with the specified set of domains.
     *
     * This method MUST retain the state of the current instance,
     * and return a new instance that contains the specified set of domains.
     *
     * @return static
     */
    public function withDomain(?string $domain): self;

    /**
     * Gets the path of the cookie.
     *
     * @return string
     */
    public function getPath(): ?string;

    /**
     * Returns an instance with the specified set of paths.
     *
     * This method MUST retain the state of the current instance,
     * and return a new instance that contains the specified set of paths.
     *
     * @return static
     */
    public function withPath(?string $path): self;

    /**
     * Whether the cookie should only be transmitted over a secure HTTPS connection.
     */
    public function isSecure(): bool;

    /**
     * Returns an instance with the specified enabling or
     * disabling cookie transmission over a secure HTTPS connection.
     *
     * This method MUST retain the state of the current instance,
     * and return a new instance that contains the specified security flag.
     *
     * @return static
     */
    public function withSecure(bool $secure = true): self;

    /**
     * Whether the cookie can be accessed only through the HTTP protocol.
     */
    public function isHttpOnly(): bool;

    /**
     * Returns an instance with the specified enable or
     * disable cookie transmission over the HTTP protocol only.
     *
     * This method MUST retain the state of the current instance,
     * and return a new instance that contains the specified httpOnly flag.
     *
     * @return static
     */
    public function withHttpOnly(bool $httpOnly = true): self;

    /**
     * Gets the SameSite attribute.
     */
    public function getSameSite(): ?string;

    /**
     * Returns an instance with the specified SameSite attribute.
     *
     * This method MUST retain the state of the current instance,
     * and return a new instance that contains the specified SameSite attribute.
     *
     * @return static
     */
    public function withSameSite(?string $sameSite): self;

    /**
     * Whether this cookie is a session cookie.
     *
     * @see withExpires()
     */
    public function isSession(): bool;
}
