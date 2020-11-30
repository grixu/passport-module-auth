<?php

namespace Grixu\PassportModuleAuth\Tests;

use Grixu\PassportModuleAuth\Exceptions\ClientNotFoundException;
use Illuminate\Http\JsonResponse;
use Orchestra\Testbench\TestCase;

/**
 * Class ClientNotFoundExceptionTest
 * @package Grixu\PassportModuleAuth\Tests
 */
class ClientNotFoundExceptionTest extends TestCase
{
    /** @test */
    public function check_render()
    {
        $obj = new ClientNotFoundException();
        $returnedObj = $obj->render();

        $this->assertEquals(JsonResponse::class, get_class($returnedObj));
        $this->assertJson($returnedObj->getContent());
        $this->assertEquals(403, $returnedObj->getStatusCode());
    }
}
