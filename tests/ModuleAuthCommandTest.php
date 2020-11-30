<?php

namespace Grixu\PassportModuleAuth\Tests;

use Grixu\PassportModuleAuth\Models\ClientModule;
use Grixu\PassportModuleAuth\Tests\Helpers\BaseTestCase;
use Laravel\Passport\Client;

/**
 * Class ModuleAuthCommandTest
 * @package Grixu\PassportModuleAuth\Tests
 */
class ModuleAuthCommandTest extends BaseTestCase
{
    protected function clearConfig($app)
    {
        $app->config->set('passport-module-auth.modules', null);
    }

    protected function brokeConfig($app)
    {
        $app->config->set('passport-module-auth.modules', 'lol');
    }

    /** @test */
    public function menu_is_ok()
    {
        $this->artisan('passport:module-auth')
            ->expectsOutput('Passport Module Auth')
            ->expectsChoice(
                'Select command:',
                'Exit',
                ['Show modules', 'Show entries', 'Add entry', 'Delete entry', 'Exit']
            )
            ->assertExitCode(0);
    }

    /** @test */
    public function modules_showed()
    {
        $this->artisan('passport:module-auth')
            ->expectsOutput('Passport Module Auth')
            ->expectsChoice(
                'Select command:',
                'Show modules',
                ['Show modules', 'Show entries', 'Add entry', 'Delete entry', 'Exit']
            )
            ->expectsOutput('Modules available in Module Auth system:')
            ->expectsConfirmation('Would you like come back to menu?')
            ->assertExitCode(0);
    }

    /** @test */
    public function modules_showed_and_come_back()
    {
        $this->artisan('passport:module-auth')
            ->expectsOutput('Passport Module Auth')
            ->expectsChoice(
                'Select command:',
                'Show modules',
                ['Show modules', 'Show entries', 'Add entry', 'Delete entry', 'Exit']
            )
            ->expectsOutput('Modules available in Module Auth system:')
            ->expectsConfirmation('Would you like come back to menu?', 'yes')
            ->expectsOutput('Passport Module Auth')
            ->expectsChoice(
                'Select command:',
                'Exit',
                ['Show modules', 'Show entries', 'Add entry', 'Delete entry', 'Exit']
            )
            ->assertExitCode(0);
    }

    /**
     * @test
     * @environment-setup clearConfig
     */
    public function no_modules_info_showed()
    {
        $this->artisan('passport:module-auth')
            ->expectsOutput('Passport Module Auth')
            ->expectsChoice(
                'Select command:',
                'Show modules',
                ['Show modules', 'Show entries', 'Add entry', 'Delete entry', 'Exit']
            )
            ->expectsOutput('Empty or broken configuration! Check passport-module-auth.php config file.')
            ->expectsOutput('Passport Module Auth')
            ->expectsChoice(
                'Select command:',
                'Exit',
                ['Show modules', 'Show entries', 'Add entry', 'Delete entry', 'Exit']
            )
            ->assertExitCode(0);
    }

    /**
     * @test
     * @environment-setup brokeConfig
     */
    public function broke_config_info_showed_in_modules()
    {
        $this->artisan('passport:module-auth')
            ->expectsOutput('Passport Module Auth')
            ->expectsChoice(
                'Select command:',
                'Show modules',
                ['Show modules', 'Show entries', 'Add entry', 'Delete entry', 'Exit']
            )
            ->expectsOutput('Empty or broken configuration! Check passport-module-auth.php config file.')
            ->expectsOutput('Passport Module Auth')
            ->expectsChoice(
                'Select command:',
                'Exit',
                ['Show modules', 'Show entries', 'Add entry', 'Delete entry', 'Exit']
            )
            ->assertExitCode(0);
    }

    /** @test */
    public function entries_show()
    {
        $this->createModuleClient();

        $this->artisan('passport:module-auth')
            ->expectsOutput('Passport Module Auth')
            ->expectsChoice(
                'Select command:',
                'Show entries',
                ['Show modules', 'Show entries', 'Add entry', 'Delete entry', 'Exit']
            )
            ->expectsOutput('Entries in Module Auth system:')
            ->expectsConfirmation('Would you like come back to menu?', 'no')
            ->assertExitCode(0);
    }

    /** @test */
    public function no_entries_info_showed()
    {
        ClientModule::query()->delete();

        $this->artisan('passport:module-auth')
            ->expectsOutput('Passport Module Auth')
            ->expectsChoice(
                'Select command:',
                'Show entries',
                ['Show modules', 'Show entries', 'Add entry', 'Delete entry', 'Exit']
            )
            ->expectsOutput('There is no entries.')
            ->expectsOutput('Passport Module Auth')
            ->expectsChoice(
                'Select command:',
                'Exit',
                ['Show modules', 'Show entries', 'Add entry', 'Delete entry', 'Exit']
            )
            ->assertExitCode(0);
    }

    /** @test */
    public function entry_added()
    {
        $client = $this->createPassportClient();
        ClientModule::query()->delete();

        $this->assertDatabaseCount('client_modules', 0);

        $this->artisan('passport:module-auth')
            ->expectsOutput('Passport Module Auth')
            ->expectsChoice(
                'Select command:',
                'Add entry',
                ['Show modules', 'Show entries', 'Add entry', 'Delete entry', 'Exit']
            )
            ->expectsOutput('Adding new entry to Module Auth system')
            ->expectsOutput('Available clients in Passport:')
            ->expectsQuestion('Type selected client ID:', $client->id)
            ->expectsOutput('Available modules in Module Auth system')
            ->expectsChoice('Select one of systems:', 'product', config('passport-module-auth.modules'))
            ->expectsOutput('Record added')
            ->expectsConfirmation('Would you like come back to menu?')
            ->assertExitCode(0);

        $this->assertDatabaseCount('client_modules', 1);
    }

    /** @test */
    public function entry_adding_failed_no_passport_client()
    {
        Client::query()->delete();
        $this->assertDatabaseCount('oauth_clients', 0);

        $this->artisan('passport:module-auth')
            ->expectsOutput('Passport Module Auth')
            ->expectsChoice(
                'Select command:',
                'Add entry',
                ['Show modules', 'Show entries', 'Add entry', 'Delete entry', 'Exit']
            )
            ->expectsOutput('Adding new entry to Module Auth system')
            ->expectsOutput('There are no clients in Passport. Create one and come back.')
            ->assertExitCode(0);
    }

    /**
     * @test
     * @environment-setup clearConfig
     */
    public function entry_adding_failed_no_config()
    {
        $client = $this->createPassportClient();
        ClientModule::query()->delete();

        $this->assertDatabaseCount('client_modules', 0);

        $this->artisan('passport:module-auth')
            ->expectsOutput('Passport Module Auth')
            ->expectsChoice(
                'Select command:',
                'Add entry',
                ['Show modules', 'Show entries', 'Add entry', 'Delete entry', 'Exit']
            )
            ->expectsOutput('Adding new entry to Module Auth system')
            ->expectsOutput('Empty or broken configuration! Check passport-module-auth.php config file.')
            ->expectsOutput('Passport Module Auth')
            ->expectsChoice(
                'Select command:',
                'Exit',
                ['Show modules', 'Show entries', 'Add entry', 'Delete entry', 'Exit']
            )
            ->assertExitCode(0);
    }

    /**
     * @test
     * @environment-setup brokeConfig
     */
    public function entry_adding_failed_broken_config()
    {
        $client = $this->createPassportClient();
        ClientModule::query()->delete();

        $this->assertDatabaseCount('client_modules', 0);

        $this->artisan('passport:module-auth')
            ->expectsOutput('Passport Module Auth')
            ->expectsChoice(
                'Select command:',
                'Add entry',
                ['Show modules', 'Show entries', 'Add entry', 'Delete entry', 'Exit']
            )
            ->expectsOutput('Adding new entry to Module Auth system')
            ->expectsOutput('Empty or broken configuration! Check passport-module-auth.php config file.')
            ->expectsOutput('Passport Module Auth')
            ->expectsChoice(
                'Select command:',
                'Exit',
                ['Show modules', 'Show entries', 'Add entry', 'Delete entry', 'Exit']
            )
            ->assertExitCode(0);
    }

    /** @test */
    public function entry_adding_bounce_on_wrong_client_id()
    {
        $client = $this->createPassportClient();
        ClientModule::query()->delete();

        $this->assertDatabaseCount('client_modules', 0);

        $this->artisan('passport:module-auth')
            ->expectsOutput('Passport Module Auth')
            ->expectsChoice(
                'Select command:',
                'Add entry',
                ['Show modules', 'Show entries', 'Add entry', 'Delete entry', 'Exit']
            )
            ->expectsOutput('Adding new entry to Module Auth system')
            ->expectsOutput('Available clients in Passport:')
            ->expectsQuestion('Type selected client ID:', $client->id+1)
            ->expectsOutput('No such client')
            ->expectsOutput('Adding new entry to Module Auth system')
            ->expectsOutput('Available clients in Passport:')
            ->expectsQuestion('Type selected client ID:', $client->id)
            ->expectsOutput('Available modules in Module Auth system')
            ->expectsChoice('Select one of systems:', 'product', config('passport-module-auth.modules'))
            ->expectsOutput('Record added')
            ->expectsConfirmation('Would you like come back to menu?')
            ->assertExitCode(0);

        $this->assertDatabaseCount('client_modules', 1);
    }

    /** @test */
    public function entry_deleted()
    {
        $cm = $this->createModuleClient();

        $this->artisan('passport:module-auth')
            ->expectsOutput('Passport Module Auth')
            ->expectsChoice(
                'Select command:',
                'Delete entry',
                ['Show modules', 'Show entries', 'Add entry', 'Delete entry', 'Exit']
            )
            ->expectsOutput('Delete entry from Module Auth system')
            ->expectsOutput('Entries in Module Auth system:')
            ->expectsQuestion('Select ID to delete (type 0 to exit): ', $cm->id)
            ->expectsOutput('Entry deleted')
            ->expectsConfirmation('Would you like come back to menu?', 'no')
            ->assertExitCode(0);
    }

    /** @test */
    public function entry_delete_failed_no_entries()
    {
        $this->artisan('passport:module-auth')
            ->expectsOutput('Passport Module Auth')
            ->expectsChoice(
                'Select command:',
                'Delete entry',
                ['Show modules', 'Show entries', 'Add entry', 'Delete entry', 'Exit']
            )
            ->expectsOutput('There is no entries.')
            ->expectsOutput('Passport Module Auth')
            ->expectsChoice(
                'Select command:',
                'Exit',
                ['Show modules', 'Show entries', 'Add entry', 'Delete entry', 'Exit']
            )
            ->assertExitCode(0);
    }

    /** @test */
    public function entry_delete_bounce_on_wrong_id()
    {
        $cm = $this->createModuleClient();

        $this->artisan('passport:module-auth')
            ->expectsOutput('Passport Module Auth')
            ->expectsChoice(
                'Select command:',
                'Delete entry',
                ['Show modules', 'Show entries', 'Add entry', 'Delete entry', 'Exit']
            )
            ->expectsOutput('Delete entry from Module Auth system')
            ->expectsOutput('Entries in Module Auth system:')
            ->expectsQuestion('Select ID to delete (type 0 to exit): ', $cm->id + 1)
            ->expectsOutput('Wrong ID')
            ->expectsOutput('Delete entry from Module Auth system')
            ->expectsOutput('Entries in Module Auth system:')
            ->expectsQuestion('Select ID to delete (type 0 to exit): ', $cm->id)
            ->expectsOutput('Entry deleted')
            ->expectsConfirmation('Would you like come back to menu?', 'no')
            ->assertExitCode(0);
    }

    /** @test */
    public function entry_delete_exit_on_zero_id_passed()
    {
        $cm = $this->createModuleClient();

        $this->artisan('passport:module-auth')
            ->expectsOutput('Passport Module Auth')
            ->expectsChoice(
                'Select command:',
                'Delete entry',
                ['Show modules', 'Show entries', 'Add entry', 'Delete entry', 'Exit']
            )
            ->expectsOutput('Delete entry from Module Auth system')
            ->expectsOutput('Entries in Module Auth system:')
            ->expectsQuestion('Select ID to delete (type 0 to exit): ', 0)
            ->assertExitCode(0);
    }

    private function createModuleClient(): ClientModule
    {
        return ClientModule::create(
            [
                'module' => 'test',
                'client_id' => 1,
            ]
        );
    }

    private function createPassportClient(): Client
    {
        return Client::create(
            [
                'name' => 'Test',
                'secret' => 'kye5b2xYZgyF7BErtWjlNlx31oJ68MiC1VtthmB0',
                'redirect' => ' ',
                'revoked' => 0,
                'personal_access_client' => 0,
                'password_client' => 0,
            ]
        );
    }
}
