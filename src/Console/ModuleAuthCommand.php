<?php

namespace Grixu\PassportModuleAuth\Console;

use Grixu\PassportModuleAuth\Models\ClientModule;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Schema;
use Laravel\Passport\Client;

class ModuleAuthCommand extends Command
{
    protected $signature = 'passport:module-auth';

    protected $description = 'Grant access to one of available modules for selected Passport Client';

    public function showModules()
    {
        $this->info('Modules available in Module Auth system:');

        if (empty(config('passport-module-auth.modules')) || !is_array(config('passport-module-auth.modules'))) {
            $this->warn('Empty or broken configuration! Check passport-module-auth.php config file.');
            return $this->handle();
        }

        $this->info('---------------------------------------');
        foreach (config('passport-module-auth.modules') as $value) {
            $this->info('- '. $value);
        }

        return $this->comeBackChoice();
    }

    public function showEntries()
    {
        if ($this->checkEntriesExists()) {
            return $this->handle();
        }

        $this->printEntries();

        return $this->comeBackChoice();
    }

    public function addEntry()
    {
        $this->info('Adding new entry to Module Auth system');
        $this->info('---------------------------------------');

        if (Client::query()->count() <= 0) {
            $this->error('There are no clients in Passport. Create one and come back.');
            return 0;
        }

        if (empty(config('passport-module-auth.modules')) || !is_array(config('passport-module-auth.modules'))) {
            $this->warn('Empty or broken configuration! Check passport-module-auth.php config file.');
            return $this->handle();
        }

        $clients = Client::query()->select('id', 'name')->get()->toArray();
        $this->info('Available clients in Passport:');
        $this->table(['ID', 'Name'], $clients);

        $clientId = $this->ask('Type selected client ID:');
        $client = Client::find($clientId);

        if ($client == null) {
            $this->error('No such client');
            return $this->addEntry();
        }

        $this->info('Available modules in Module Auth system');
        $module = $this->choice('Select one of systems:', config('passport-module-auth.modules'));

        ClientModule::create([
            'client_id' => $client->id,
            'module' => $module,
                             ]);

        $this->info('Record added');

        return $this->comeBackChoice();
    }

    public function deleteEntry()
    {
        if ($this->checkEntriesExists()) {
            return $this->handle();
        }

        $this->info('Delete entry from Module Auth system');
        $this->info('---------------------------------------');

        $this->printEntries();

        $deleteId = $this->ask('Select ID to delete (type 0 to exit): ');

        if ($deleteId == '0') {
            return 0;
        }

        $clientModule = ClientModule::find($deleteId);

        if ($clientModule == null) {
            $this->error('Wrong ID');
            return $this->deleteEntry();
        }

        $clientModule->delete();

        $this->info('Entry deleted');

        return $this->comeBackChoice();
    }

    public function handle()
    {
        if (!Schema::hasTable('client_modules')) {
            $this->error('No Module Auth table detected. Run migrations first!');
            return 0;
        }

        $this->info('Passport Module Auth');
        $this->info('---------------------');
        $choice = $this->choice('Select command:', ['Show modules', 'Show entries', 'Add entry', 'Delete entry', 'Exit'], 'Show modules');

        switch ($choice) {
            case 'Show modules':
                return $this->showModules();
            case 'Show entries':
                return $this->showEntries();
            case 'Add entry':
                return $this->addEntry();
            case 'Delete entry':
                return $this->deleteEntry();
        }

        return 0;
    }

    private function checkEntriesExists()
    {
        if (ClientModule::count() <= 0) {
            $this->error('There is no entries.');
            return true;
        }

        return false;
    }

    private function printEntries()
    {
        $data = ClientModule::query()->orderBy('module')->get(['id', 'module', 'client_id'])->toArray();
        $headers = ['ID', 'module', 'client_id'];

        $this->info('Entries in Module Auth system:');
        $this->info('------------------------------');
        $this->table($headers, $data);
    }

    private function comeBackChoice()
    {
        $choice = $this->confirm('Would you like come back to menu?');

        if ($choice) {
            return $this->handle();
        }

        return 0;
    }
}
