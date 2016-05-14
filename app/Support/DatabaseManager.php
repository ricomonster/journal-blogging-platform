<?php //-->
namespace Journal\Support;

use Artisan;

/**
 * Class DatabaseManager
 * @package Journal\Support
 */
class DatabaseManager
{
    /**
     * Fire up the database seeder
     *
     * @return mixed
     */
    public function seed()
    {
        // database seed
        return Artisan::call('db:seed');
    }

    /**
     * Performs the migration
     *
     * @return mixed
     */
    public function migrate()
    {
        return $this->performMigration();
    }

    /**
     * Clears the compilation, reset the database if ever there's an existing
     * content, then migrate the database.
     *
     * @return mixed
     */
    protected function performMigration()
    {
        // reset first
        Artisan::call('migrate:reset');

        // we're going to assume that everything went well so we're going to
        // run the migration again
        $migration = Artisan::call('migrate');

        // let the one who called this class to perform some validation.
        return $migration;
    }
}
