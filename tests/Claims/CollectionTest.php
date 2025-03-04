<?php

/*
 * This file is part of jwt-auth.
 *
 * (c) Sean Tymon <tymon148@gmail.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Tymon\JWTAuth\Test\Claims;

use Tymon\JWTAuth\Claims\Collection;
use Tymon\JWTAuth\Claims\Expiration;
use Tymon\JWTAuth\Claims\IssuedAt;
use Tymon\JWTAuth\Claims\Issuer;
use Tymon\JWTAuth\Claims\JwtId;
use Tymon\JWTAuth\Claims\NotBefore;
use Tymon\JWTAuth\Claims\Subject;
use Tymon\JWTAuth\Test\AbstractTestCase;

class CollectionTest extends AbstractTestCase
{
    private function getCollection()
    {
        $claims = [
            new Subject(1),
            new Expiration($this->testNowTimestamp + 3600),
            new IssuedAt($this->testNowTimestamp),
        ];

        return new Collection($claims);
    }

    /** @test */
    public function it_should_determine_if_a_collection_contains_all_the_given_claims()
    {
        $collection = $this->getCollection();

        $this->assertFalse($collection->hasAllClaims(['user_id', 'exp', 'orig_iat', 'abc']));
        $this->assertFalse($collection->hasAllClaims(['foo', 'bar']));
        $this->assertFalse($collection->hasAllClaims([]));

        $this->assertTrue($collection->hasAllClaims(['user_id', 'exp', 'orig_iat']));
    }

    /** @test */
    public function it_should_get_a_claim_instance_by_name()
    {
        $collection = $this->getCollection();

        $this->assertInstanceOf(Expiration::class, $collection->getByClaimName('exp'));
        $this->assertInstanceOf(Subject::class, $collection->getByClaimName('user_id'));
    }
}
