<?php

namespace Grixu\PassportModuleAuth\Tests\Helpers;

use CreateClientModuleTable;
use CreateOauthAccessTokensTable;
use CreateOauthClientsTable;
use Grixu\PassportModuleAuth\PassportModuleAuthServiceProvider;
use Orchestra\Testbench\TestCase;

/**
 * Class BaseTestCase
 * @package Grixu\PassportModuleAuth\Tests\Helpers
 */
class BaseTestCase extends TestCase
{
    protected function getPackageProviders($app)
    {
        return [PassportModuleAuthServiceProvider::class];
    }

    protected function getEnvironmentSetUp($app)
    {
        parent::getEnvironmentSetUp($app);

        include_once __DIR__.'/../../database/migrations/2020_11_26_123339_create_client_module_table.php';
        include_once __DIR__.'/../../vendor/laravel/passport/database/migrations/2016_06_01_000002_create_oauth_access_tokens_table.php';
        include_once __DIR__.'/../../vendor/laravel/passport/database/migrations/2016_06_01_000004_create_oauth_clients_table.php';

        (new CreateClientModuleTable())->up();
        (new CreateOauthAccessTokensTable())->up();
        (new CreateOauthClientsTable())->up();

    }
}
