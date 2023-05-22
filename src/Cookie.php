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

final class Cookie implements CookieInterface
{
    /**
     * SameSite policy `None`.
     */
    public const SAME_SITE_NONE = 'None';

    /**
     * SameSite policy `Lax`.
     */
    public const SAME_SITE_LAX = 'Lax';

    /**
     * SameSite policy `Strict`.
     */
    public const SAME_SITE_STRICT = 'Strict';

    /**
     * @var string
     */
    private $name;

    /**
     * @var string
     */
    private $value;

    /**
     * @var int
     */
    private $expires;

    /**
     * @var string|null
     */
    private $domain;

    /**
     * @var string|null
     */
    private $path;

    /**
     * @var bool|null
     */
    private $secure;

    /**
     * @var bool|null
     */
    private $httpOnly;

    /**
     * @var string|null
     */
    private $sameSite;

    /**
     * @param string                             $name     the name of the cookie
     * @param string                             $value    the value of the cookie
     * @param \DateTimeInterface|int|string|null $expire   the time the cookie expire
     * @param string|null                        $path     the set of paths for the cookie
     * @param string|null                        $domain   the set of domains for the cookie
     * @param bool|null                          $secure   whether the cookie should only be transmitted over a secure HTTPS connection
     * @param bool|null                          $httpOnly whether the cookie can be accessed only through the HTTP protocol
     * @param string|null                        $sameSite whether the cookie will be available for cross-site requests
     *
     * @throws \InvalidArgumentException if one or more arguments are not valid
     */
    public function __construct(
        string $name,
        string $value = '',
        $expire = null,
        string $domain = null,
        ?string $path = '/',
        ?bool $secure = true,
        ?bool $httpOnly = true,
        ?string $sameSite = self::SAME_SITE_LAX
    ) {
        $this->setName($name);
        $this->setValue($value);
        $this->setExpires($expire);
        $this->setDomain($domain);
        $this->setPath($path);
        $this->setSecure($secure);
        $this->setHttpOnly($httpOnly);
        $this->setSameSite($sameSite);
    }

    /**
     * {@inheritDoc}
     */
    public function __toString(): string
    {
        $cookie = $this->name.'='.rawurlencode($this->value);

        if (!$this->isSession()) {
            $cookie .= '; Expires='.gmdate('D, d-M-Y H:i:s T', $this->expires);
            $cookie .= '; Max-Age='.$this->getMaxAge();
        }

        if (null !== $this->domain) {
            $cookie .= '; Domain='.$this->domain;
        }

        if (null !== $this->path) {
            $cookie .= '; Path='.$this->path;
        }

        if (true === $this->secure) {
            $cookie .= '; Secure';
        }

        if (true === $this->httpOnly) {
            $cookie .= '; HttpOnly';
        }

        if (null !== $this->sameSite) {
            $cookie .= '; SameSite='.$this->sameSite;
        }

        return $cookie;
    }

    /**
     * {@inheritDoc}
     */
    public function getName(): string
    {
        return $this->name;
    }

    /**
     * {@inheritDoc}
     */
    public function getValue(): string
    {
        return $this->value;
    }

    /**
     * {@inheritDoc}
     */
    public function withValue(string $value): CookieInterface
    {
        if ($value === $this->value) {
            return $this;
        }

        $self = $this->clone();
        $self->setValue($value);

        return $self;
    }

    /**
     * {@inheritDoc}
     */
    public function getMaxAge(): int
    {
        $maxAge = $this->expires - time();

        return $maxAge > 0 ? $maxAge : 0;
    }

    /**
     * {@inheritDoc}
     */
    public function getExpires(): int
    {
        return $this->expires;
    }

    /**
     * {@inheritDoc}
     */
    public function isExpired(): bool
    {
        return !$this->isSession() && $this->expires < time();
    }

    /**
     * {@inheritDoc}
     */
    public function expire(): CookieInterface
    {
        if ($this->isExpired()) {
            return $this;
        }

        $self = $this->clone();
        $self->expires = time() - 31536001;

        return $self;
    }

    /**
     * {@inheritDoc}
     *
     * @throws \InvalidArgumentException if the expire time is not valid
     */
    public function withExpires($expire = null): CookieInterface
    {
        if ($expire === $this->expires) {
            return $this;
        }

        $self = $this->clone();
        $self->setExpires($expire);

        return $self;
    }

    /**
     * {@inheritDoc}
     */
    public function getDomain(): ?string
    {
        return $this->domain;
    }

    /**
     * {@inheritDoc}
     */
    public function withDomain(?string $domain): CookieInterface
    {
        if ($domain === $this->domain) {
            return $this;
        }

        $self = $this->clone();
        $self->setDomain($domain);

        return $self;
    }

    /**
     * {@inheritDoc}
     */
    public function getPath(): ?string
    {
        return $this->path;
    }

    /**
     * {@inheritDoc}
     */
    public function withPath(?string $path): CookieInterface
    {
        if ($path === $this->path) {
            return $this;
        }

        $self = $this->clone();
        $self->setPath($path);

        return $self;
    }

    /**
     * {@inheritDoc}
     */
    public function isSecure(): bool
    {
        return $this->secure ?? false;
    }

    /**
     * {@inheritDoc}
     */
    public function withSecure(bool $secure = true): CookieInterface
    {
        if ($secure === $this->secure) {
            return $this;
        }

        $self = $this->clone();
        $self->setSecure($secure);

        return $self;
    }

    /**
     * {@inheritDoc}
     */
    public function isHttpOnly(): bool
    {
        return $this->httpOnly ?? false;
    }

    /**
     * {@inheritDoc}
     */
    public function withHttpOnly(bool $httpOnly = true): CookieInterface
    {
        if ($httpOnly === $this->httpOnly) {
            return $this;
        }

        $self = $this->clone();
        $self->setHttpOnly($httpOnly);

        return $self;
    }

    /**
     * {@inheritDoc}
     */
    public function getSameSite(): ?string
    {
        return $this->sameSite;
    }

    /**
     * {@inheritDoc}
     *
     * @throws \InvalidArgumentException if the sameSite is not valid
     */
    public function withSameSite(?string $sameSite): CookieInterface
    {
        if ($sameSite === $this->sameSite) {
            return $this;
        }

        $self = $this->clone();
        $self->setSameSite($sameSite);

        return $self;
    }

    /**
     * {@inheritDoc}
     */
    public function isSession(): bool
    {
        return 0 === $this->expires;
    }

    /**
     * @throws \InvalidArgumentException if the name is not valid
     */
    private function setName(string $name): void
    {
        if (empty($name)) {
            throw new \InvalidArgumentException('The cookie name cannot be empty.');
        }

        if (!preg_match('/^[a-zA-Z0-9!#$%&\' *+\-.^_`|~]+$/', $name)) {
            throw new \InvalidArgumentException(sprintf(
                'The cookie name `%s` contains invalid characters; must contain any US-ASCII'
                .' characters, except control and separator characters, spaces, or tabs.',
                $name
            ));
        }

        $this->name = $name;
    }

    private function setValue(string $value): void
    {
        $this->value = $value;
    }

    /**
     * @param mixed $expire
     *
     * @throws \InvalidArgumentException if the expire time is not valid
     */
    private function setExpires($expire): void
    {
        if (null !== $expire && !\is_int($expire) && !\is_string($expire) && !$expire instanceof \DateTimeInterface) {
            throw new \InvalidArgumentException(sprintf(
                'The cookie expire time is not valid; must be null, or string,'
                .' or integer, or DateTimeInterface instance; received `%s`.',
                (\is_object($expire) ? \get_class($expire) : \gettype($expire))
            ));
        }

        if (empty($expire)) {
            $this->expires = 0;

            return;
        }

        if ($expire instanceof \DateTimeInterface) {
            $expire = $expire->format('U');
        } elseif (!is_numeric($expire)) {
            $stringExpire = $expire;
            $expire = strtotime($expire);

            if (false === $expire) {
                throw new \InvalidArgumentException(sprintf(
                    'The string representation of the cookie expire time `%s` is not valid.',
                    $stringExpire
                ));
            }
        }
        $this->expires = ($expire > 0) ? (int) $expire : 0;
    }

    private function setDomain(?string $domain): void
    {
        $this->domain = empty($domain) ? null : $domain;
    }

    private function setPath(?string $path): void
    {
        $this->path = empty($path) ? null : $path;
    }

    private function setSecure(?bool $secure): void
    {
        $this->secure = $secure;
    }

    private function setHttpOnly(?bool $httpOnly): void
    {
        $this->httpOnly = $httpOnly;
    }

    /**
     * @throws \InvalidArgumentException
     */
    private function setSameSite(?string $sameSite): void
    {
        $sameSite = empty($sameSite) ? null : ucfirst(strtolower($sameSite));
        $sameSiteValues = [self::SAME_SITE_NONE, self::SAME_SITE_LAX, self::SAME_SITE_STRICT];

        if (null !== $sameSite && !\in_array($sameSite, $sameSiteValues, true)) {
            throw new \InvalidArgumentException(sprintf(
                'The sameSite attribute `%s` is not valid; must be one of (%s).',
                $sameSite,
                implode(', ', array_map(static fn ($item) => "\"{$item}\"", $sameSiteValues))
            ));
        }

        $this->sameSite = $sameSite;
    }

    /**
     * Returns a clone of the current object.
     *
     * @return self
     */
    private function clone()
    {
        return clone $this;
    }
}
