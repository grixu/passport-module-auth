<?php

namespace Grixu\PassportModuleAuth\Tests;

use Grixu\PassportModuleAuth\Exceptions\AccessDeniedException;
use Grixu\PassportModuleAuth\Exceptions\ClientNotFoundException;
use Grixu\PassportModuleAuth\Exceptions\EmptyConfigException;
use Grixu\PassportModuleAuth\Exceptions\NotValidModuleException;
use Grixu\PassportModuleAuth\Middleware\ModuleAuthMiddleware;
use Grixu\PassportModuleAuth\Tests\Helpers\BaseTestCase;
use Illuminate\Http\Request;

/**
 * Class ModuleAuthMiddlewareEmptyConfigTest
 * @package Grixu\PassportModuleAuth\Tests
 */
class ModuleAuthMiddlewareEmptyConfigTest extends BaseTestCase
{
    protected function getEnvironmentSetUp($app)
    {
        parent::getEnvironmentSetUp($app);

        $app['config']->set('passport-module-auth.modules', null);
    }

    /** @test */
    public function empty_config()
    {
        $obj = new ModuleAuthMiddleware();

        try {
            $result = $obj->handle(new Request(), \Closure::fromCallable(function ($request) {
            }), 'product');

            $this->assertTrue(false);
        } catch (AccessDeniedException $e) {
            $this->assertTrue(false);
        } catch (ClientNotFoundException $e) {
            $this->assertTrue(false);
        } catch (EmptyConfigException $e) {
            $this->assertTrue(true);
        } catch (NotValidModuleException $e) {
            $this->assertTrue(false);
        }
    }
}
