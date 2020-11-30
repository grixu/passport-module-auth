<?php

namespace Grixu\PassportModuleAuth\Tests;

use Grixu\PassportModuleAuth\Exceptions\NotValidModuleException;
use Illuminate\Http\JsonResponse;
use Orchestra\Testbench\TestCase;

/**
 * Class NotValidModuleExceptionTest
 * @package Grixu\PassportModuleAuth\Tests
 */
class NotValidModuleExceptionTest extends TestCase
{
    /** @test */
    public function check_render()
    {
        $obj = new NotValidModuleException();
        $returnedObj = $obj->render();

        $this->assertEquals(JsonResponse::class, get_class($returnedObj));
        $this->assertJson($returnedObj->getContent());
        $this->assertEquals(403, $returnedObj->getStatusCode());
    }
}
