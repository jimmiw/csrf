<?php

namespace Westsworld\CSRF\Tests;

use ArrayObject;
use PHPUnit\Framework\TestCase;
use Westsworld\CSRF\Csrf;

class CsrfTest extends TestCase
{
    /**
     * Tests that we can generate a new token and that it's found
     * in the given session storage engine.
     */
    public function testTokenGeneration()
    {
        $session = new ArrayObject();
        $tokenHandler = new Csrf($session);
        $token = $tokenHandler->generateToken();
        // making sure that the actual session is empty, since a session handler was passed to the constructor
        static::assertEmpty($_SESSION);

        static::assertNotNull($session[Csrf::TOKEN_SESSION_KEY]);
        static::assertEquals($token, $session[Csrf::TOKEN_SESSION_KEY]);
    }

    public function testResetCurrentToken()
    {
        $session = new ArrayObject();
        $tokenHandler = new Csrf($session);
        $token = $tokenHandler->generateToken();
        static::assertEmpty($_SESSION);

        // testing that we have a token in the session
        static::assertNotNull($session[Csrf::TOKEN_SESSION_KEY]);

        $tokenHandler->resetToken();
        // the token should now be removed
        static::assertNull($session[Csrf::TOKEN_SESSION_KEY]);

    }

    public function testValidateCurrentToken()
    {
        $session = new ArrayObject();
        $tokenHandler = new Csrf($session);
        $token = $tokenHandler->generateToken();
        static::assertEmpty($_SESSION);

        // testing if we can validate the current token
        static::assertTrue($tokenHandler->validateToken($token));

        // removing current token
        $tokenHandler->resetToken();
        // should no longer be able to validate the token
        static::assertFalse($tokenHandler->validateToken($token));

        // generating two token, where token2 should validate, but not token1 since it's too old
        $token1 = $tokenHandler->generateToken();
        $token2 = $tokenHandler->generateToken();
        static::assertTrue($tokenHandler->validateToken($token2));
    }

    public function testSessionHandling()
    {
        session_start();
        $tokenHandler = new Csrf();
        $token = $tokenHandler->generateToken();

        static::assertNotNull($_SESSION[Csrf::TOKEN_SESSION_KEY]);
    }
}
