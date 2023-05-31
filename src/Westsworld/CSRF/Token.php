<?php

namespace Westsworld\CSRF;

/**
 * A token class that handles storing info about a token.
 * It holds the value, but also a key that should be used for the forms.
 */
class Token 
{
    private $key;
    private $value;

    /**
     * Creates a new token, that is immutable
     * @param string $value the actual token value
     */
    public function __construct(string $value, ?string $key)
    {
        $this->value = $value;
        $this->key = $key;
    }

    /**
     * Fetches the current value
     * @return string
     */
    public function getValue(): string
    {
        return $this->value;
    }

    /**
     * Fetches the current session key / form field name
     * @return string
     */
    public function getKey(): string
    {
        // if no token key is set, we generate a new random one
        if (empty ($this->key)) {
            $this->key = 'ww_csrf_token_' . bin2hex(random_bytes(10));
        }

        return $this->key;
    }
}
