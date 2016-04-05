<?php //-->
namespace Journal\Support;

use Artisan;

class DatabaseManager
{
    public function migrate()
    {
        return $this->performMigration();
    }

    protected function performMigration()
    {
        // check first
        Artisan::call('clear-compiled');

        // we're going to assume that everything went well so we're going to
        // run the migration again
        $migration = Artisan::call('migrate');

        // let the one who called this class to perform some validation.
        return $migration;
    }
}
