<?php //-->
namespace Journal\Support;

class RequirementsChecker
{
    /**
     * Checks the system requirements of the server
     *
     * @param $requirements
     * @return array
     */
    public function checkExtensions(array $requirements)
    {
        $results = [];

        // initialize array key
        $results['errors'] = false;

        foreach($requirements as $requirement)
        {
            $results['requirements'][$requirement] = true;

            if(!extension_loaded($requirement))
            {
                $results['requirements'][$requirement] = false;
                $results['errors'] = true;
            }
        }

        return $results;
    }
}
