<?php

namespace Westsworld\CSRF\Tests;

use PHPUnit\Framework\TestCase;
use Westsworld\CSRF\Generator;
use Westsworld\CSRF\Token;

class CsrfTest extends TestCase
{
    /**
     * Tests that we can generate a new token and that it's found
     * in the given session storage engine.
     */
    public function testTokenGeneration()
    {
        $tokenHandler = new Generator();
        $token = $tokenHandler->generateToken();

        static::assertNotNull($_SESSION[$token->getKey()]);
        static::assertEquals($token->getValue(), $_SESSION[$token->getKey()]);
    }

    public function testResetCurrentToken()
    {
        $tokenHandler = new Generator();
        $token = $tokenHandler->generateToken();
        
        // testing that we have a token in the session
        static::assertNotNull($_SESSION[$token->getKey()]);

        $tokenHandler->resetToken($token->getKey());
        // the token should now be removed
        static::assertNull($_SESSION[$token->getKey()]);

    }

    public function testValidateCurrentToken()
    {
        $tokenHandler = new Generator();
        $token = $tokenHandler->generateToken();
    
        // testing if we can validate the current token
        static::assertTrue($tokenHandler->validateToken($token->getValue(), $token->getKey()));

        // removing current token
        $tokenHandler->resetToken($token->getKey());
        // should no longer be able to validate the token
        static::assertFalse($tokenHandler->validateToken($token->getValue(), $token->getKey()));

        // generating two token, where token2 should validate, but not token1 since it's too old
        $token1 = $tokenHandler->generateToken();
        $token2 = $tokenHandler->generateToken();
        static::assertTrue($tokenHandler->validateToken($token2->getValue(), $token2->getKey()));
    }
}
