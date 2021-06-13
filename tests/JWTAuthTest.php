<?php

/*
 * This file is part of jwt-auth.
 *
 * (c) Sean Tymon <tymon148@gmail.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Tymon\JWTAuth\Test;

use Illuminate\Http\Request;
use Mockery;
use stdClass;
use Tymon\JWTAuth\Contracts\Providers\Auth;
use Tymon\JWTAuth\Exceptions\JWTException;
use Tymon\JWTAuth\Exceptions\TokenInvalidException;
use Tymon\JWTAuth\Factory;
use Tymon\JWTAuth\Http\Parser\Parser;
use Tymon\JWTAuth\JWTAuth;
use Tymon\JWTAuth\Manager;
use Tymon\JWTAuth\Payload;
use Tymon\JWTAuth\Test\Stubs\UserStub;
use Tymon\JWTAuth\Token;

class JWTAuthTest extends AbstractTestCase
{
    /**
     * @var \Mockery\MockInterface|\Tymon\JWTAuth\Manager
     */
    protected $manager;

    /**
     * @var \Mockery\MockInterface|\Tymon\JWTAuth\Contracts\Providers\Auth
     */
    protected $auth;

    /**
     * @var \Mockery\MockInterface|\Tymon\JWTAuth\Http\Parser\Parser
     */
    protected $parser;

    /**
     * @var \Tymon\JWTAuth\JWTAuth
     */
    protected $jwtAuth;

    public function setUp(): void
    {
        $this->manager = Mockery::mock(Manager::class);
        $this->auth = Mockery::mock(Auth::class);
        $this->parser = Mockery::mock(Parser::class);
        $this->jwtAuth = new JWTAuth($this->manager, $this->auth, $this->parser);
    }

    /** @test */
    public function it_should_return_a_token_when_passing_a_user()
    {
        $payloadFactory = Mockery::mock(Factory::class);
        $payloadFactory->shouldReceive('make')->andReturn(Mockery::mock(Payload::class));

        $this->manager
             ->shouldReceive('getPayloadFactory->customClaims')
             ->once()
             ->andReturn($payloadFactory);

        $this->manager->shouldReceive('encode->get')->once()->andReturn('foo.bar.baz');

        $token = $this->jwtAuth->fromUser(new UserStub);

        $this->assertSame($token, 'foo.bar.baz');
    }

     /** @test */
     public function it_should_refresh_a_token()
     {
         $newToken = Mockery::mock(Token::class);
         $newToken->shouldReceive('get')->once()->andReturn('baz.bar.foo');
 
         $this->manager->shouldReceive('customClaims->refresh')->once()->andReturn($newToken);
 
         $result = $this->jwtAuth->setToken('foo.bar.baz')->refresh();
 
         $this->assertSame($result, 'baz.bar.foo');
     }
}
