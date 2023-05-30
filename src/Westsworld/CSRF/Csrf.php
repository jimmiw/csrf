<?php

namespace Westsworld\CSRF;

use ArrayObject;

class Csrf
{
    /** @var string TOKEN_SESSION_KEY the session key used to store tokens in */
    public const TOKEN_SESSION_KEY = 'ww_csrf_token';

    /** @var $sesstionStorage the session storage to use, must implment ArrayMethods */
    private $sessionStorage;

    /**
     * Constructs the CSRF handler, using the given sessionStorage engine
     * @param mixed $sessionStorage
     */
    public function __construct($sessionStorage = null)
    {
        $this->sessionStorage = $sessionStorage;
    }

    /**
     * Generates a new token and stores it in the session.
     * @return string the newly generated token.
     */
    public function generateToken(): string
    {
        $this->setToken(bin2hex(random_bytes(24)));

        return $this->getCurrentToken();
    }

    /**
     * Validates the given token, with the one currently in the session.
     * @return bool true if valid, else false.
     */
    public function validateToken($token): bool
    {
        // no current token? return false, since null === null could be a potential risk
        if (empty($this->getCurrentToken())) {
            return false;
        }

        return $this->getCurrentToken() === $token;
    }

    /**
     * Fetches the currently stored token.
     * @return ?string the currently stored token
     */
    public function getCurrentToken(): ?string
    {
        if (! empty($this->sessionStorage)) {
            return $this->sessionStorage[self::TOKEN_SESSION_KEY];
        } else {
            return $_SESSION[self::TOKEN_SESSION_KEY];
        }
    }

    /**
     * Sets a new token
     * @param string $token the new token to set
     */
    private function setToken(?string $token): void
    {
        if (! empty($this->sessionStorage)) {
            $this->sessionStorage[self::TOKEN_SESSION_KEY] = $token;
        } else {
            $_SESSION[self::TOKEN_SESSION_KEY] = $token;
        }
    }

    /**
     * Resets / deletes the currently stored token
     */
    public function resetToken(): void
    {
        $this->setToken(null);
    }
}
