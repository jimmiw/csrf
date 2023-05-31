<?php

namespace Westsworld\CSRF;

use ArrayObject;

class Generator
{
    /**
     * Constructs the CSRF handler, using the given sessionStorage engine
     */
    public function __construct()
    {
        // nothing atm
    }

    /**
     * Generates a new token and stores it in the session.
     * @return string the newly generated token.
     */
    public function generateToken(): Token
    {
        return $this->setToken(bin2hex(random_bytes(24)), null);
    }

    /**
     * Validates the given token, with the one currently in the session.
     * @return bool true if valid, else false.
     */
    public function validateToken(string $token, string $key): bool
    {
        // no current token? return false, since null === null could be a potential risk
        if (empty($this->getToken($key))) {
            return false;
        }

        return $this->getToken($key) === $token;
    }

    /**
     * Fetches the currently stored token.
     * @return ?string the currently stored token
     */
    public function getToken(string $key): ?string
    {
        if (empty($_SESSION[$key])) {
            return null;
        }

        return $_SESSION[$key];
    }

    /**
     * Sets a new token
     * @param string $token the new token to set
     */
    private function setToken(?string $token, ?string $key): ?Token
    {
        $dataObject = new Token($token, $key);
        $_SESSION[$dataObject->getKey()] = $dataObject->getValue();

        return $dataObject;
    }

    /**
     * Resets / deletes the currently stored token
     * @param string $key the session key to reset
     */
    public function resetToken(string $key): void
    {
        unset($_SESSION[$key]);
    }
}
