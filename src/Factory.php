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

final class Factory
{
    /**
     * Creates new factory instance.
     *
     * @return Factory
     */
    public static function new()
    {
        return new self();
    }

    /**
     * Creates an instance of the {@see Cookie} from attributes.
     *
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
     *
     * @return CookieInterface
     */
    public function create(
        string $name,
        string $value = '',
        $expire = null,
        string $domain = null,
        ?string $path = '/',
        ?bool $secure = true,
        ?bool $httpOnly = true,
        ?string $sameSite = Cookie::SAME_SITE_LAX
    ) {
        return new Cookie($name, $value, $expire, $domain, $path, $secure, $httpOnly, $sameSite);
    }

    /**
     * Creates an instance of the `HttpSoft\Cookie\Cookie` from raw `Set-Cookie` header.
     *
     * @param string $string raw `Set-Cookie` header value
     *
     * @throws \InvalidArgumentException if the raw `Set-Cookie` header value is not valid
     *
     * @return CookieInterface
     */
    public function createFromString(string $string)
    {
        if (!$attributes = preg_split('/\s*;\s*/', $string, -1, \PREG_SPLIT_NO_EMPTY)) {
            throw new \InvalidArgumentException(sprintf(
                'The raw value of the `Set Cookie` header `%s` could not be parsed.',
                $string
            ));
        }

        $nameAndValue = explode('=', array_shift($attributes), 2);
        $cookie = ['name' => $nameAndValue[0], 'value' => isset($nameAndValue[1]) ? urldecode($nameAndValue[1]) : ''];

        while ($attribute = array_shift($attributes)) {
            $attribute = explode('=', $attribute, 2);
            $attributeName = strtolower($attribute[0]);
            $attributeValue = $attribute[1] ?? null;

            if (\in_array($attributeName, ['expires', 'domain', 'path', 'samesite'], true)) {
                $cookie[$attributeName] = $attributeValue;
                continue;
            }

            if (\in_array($attributeName, ['secure', 'httponly'], true)) {
                $cookie[$attributeName] = true;
                continue;
            }

            if ('max-age' === $attributeName) {
                $cookie['expires'] = time() + (int) $attributeValue;
            }
        }

        return new Cookie(
            $cookie['name'],
            $cookie['value'],
            $cookie['expires'] ?? null,
            $cookie['domain'] ?? null,
            $cookie['path'] ?? null,
            $cookie['secure'] ?? null,
            $cookie['httponly'] ?? null,
            $cookie['samesite'] ?? null
        );
    }
}
