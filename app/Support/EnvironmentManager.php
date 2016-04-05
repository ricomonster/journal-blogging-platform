<?php //-->
namespace Journal\Support;

class EnvironmentManager
{
    const ENV           = '.env';
    const ENV_EXAMPLE   = '.env.example';

    protected $breakPoints = [
        'app_key',
        'db_password',
        'queue_driver',
        'redis_port'];

    /**
     * Fetches the content of the env file.
     *
     * @param $convertToArray
     * @return array
     */
    public function getEnv($convertToArray = false)
    {
        return $this->getEnvContent($convertToArray);
    }

    /**
     * Update the contents of the env file.
     *
     * @param $content
     * @return mixed
     */
    public function update($content)
    {
        // initialize variable
        $envContent = '';

        // loop the content because this is an array
        foreach ($content as $key => $value) {
            $envContent .= strtoupper($key)."=".$value."\n";

            // check if the key is a breakpoint
            if (in_array($key, $this->breakPoints)) {
                // add additional \n
                $envContent .= "\n";
            }
        }

        // put it in the file!
        try {
            $results = file_put_contents(base_path(self::ENV), $envContent);
        } catch (Exception $e) {
            return $e->getMessage();
        }

        return $results;
    }

    /**
     * Get the content of the env file but if the doesn't exists, it will
     * create a env file based on the env.example file provided by
     * Laravel.
     *
     * @param $convertToArray
     * @return array
     */
    protected function getEnvContent($convertToArray = false)
    {
        $env        = base_path(self::ENV);
        $envExample = base_path(self::ENV_EXAMPLE);

        // check if there's an env file
        if (!file_exists($env)) {
            if (file_exists($envExample)) {
                copy($envExample, $env);
            } else {
                touch($env);
            }
        }

        // check if we need to convert the contents into array
        if ($convertToArray) {
            return $this->envContentToArray($env);
        }

        return file_get_contents($env);
    }

    /**
     * Converts the content of the env file to an array.
     *
     * @param $envFile
     * @return array
     */
    protected function envContentToArray($envFile)
    {
        $contents = file($envFile);
        $arrayContents = [];

        // loop and set the array keys
        foreach ($contents as $key => $content) {
            // explode the content
            $line = explode('=', $content);

            // check first if there's a key
            if (empty($line[0]) || !isset($line[1])) {
                continue;
            }

            // push to the array and remove the \n in the value
            $arrayContents[strtolower($line[0])] = trim(preg_replace('/\s+/', ' ', $line[1]));
        }

        return $arrayContents;
    }
}
