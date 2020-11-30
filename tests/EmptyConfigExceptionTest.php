<?php

namespace Grixu\PassportModuleAuth\Tests;

use Grixu\PassportModuleAuth\Exceptions\EmptyConfigException;
use Illuminate\Http\JsonResponse;
use Orchestra\Testbench\TestCase;

/**
 * Class EmptyConfigExceptionTest
 * @package Grixu\PassportModuleAuth\Tests
 */
class EmptyConfigExceptionTest extends TestCase
{
    /** @test */
    public function check_render()
    {
        $obj = new EmptyConfigException();
        $returnedObj = $obj->render();

        $this->assertEquals(JsonResponse::class, get_class($returnedObj));
        $this->assertJson($returnedObj->getContent());
        $this->assertEquals(500, $returnedObj->getStatusCode());
    }
}
