<?php

namespace Grixu\PassportModuleAuth\Tests;

use Grixu\PassportModuleAuth\Exceptions\AccessDeniedException;
use Illuminate\Http\JsonResponse;
use Orchestra\Testbench\TestCase;

/**
 * Class AccessDeniedExceptionTest
 * @package Grixu\PassportModuleAuth\Tests
 */
class AccessDeniedExceptionTest extends TestCase
{
    /** @test */
    public function check_render()
    {
        $obj = new AccessDeniedException();
        $returnedObj = $obj->render();

        $this->assertEquals(JsonResponse::class, get_class($returnedObj));
        $this->assertJson($returnedObj->getContent());
        $this->assertEquals(403, $returnedObj->getStatusCode());
    }
}
