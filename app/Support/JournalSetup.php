<?php //-->
namespace Journal\Support;

/**
 * Class JournalSetup
 * @package Journal\Support
 */
class JournalSetup
{
    /**
     * Checks if there's a .env file already placed.
     */
    public static function validateEnv()
    {
        // check if the .env file exists
        if (!file_exists(base_path('.env'))) {
            // create the .env file
            copy(base_path('.env.example'), base_path('.env'));
        }
    }
}