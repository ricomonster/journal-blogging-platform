<?php //-->
namespace Journal\Http\Controllers\Installer;

use Illuminate\Http\Request;
use Journal\Http\Requests;
use Journal\Http\Controllers\Controller;
use Artisan;

class DatabaseController extends Controller
{
    const ENV           = '.env';
    const ENV_EXAMPLE   = '.env.example';

    protected $breakPoints = [
        'app_key',
        'db_password',
        'queue_driver',
        'redis_port'];

    public function page()
    {
        // just like wordpress, show a form so that the user will input
        // the database credentials and we either let the user manually
        // input the credentials in the .env file or we do it.
        // let's get the contents of the .env or .env.example
        $env = $this->getEnvContent(true);

        return view('installer.database', compact('env'));
    }

    public function setup(Request $request)
    {
        // get the content of the form and apply it to the env file
        $post = $request->all();

        // get the env content
        $env = $this->getEnvContent(true);

        // loop the post
        foreach ($post as $key => $value) {
            // update the env
            $env[$key] = $value;
        }

        // save the contents to the env file
        $this->saveEnv($env);

        return $this->performMigration();
    }

    public function getEnvContent($convertToArray = false)
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

    protected function performMigration()
    {
        // check first
        Artisan::call('clear-compiled');

        // we're going to assume that everything went well so we're going to
        // run the migration again
        $migration = Artisan::call('migrate');

        // check if it went well
        if (!$migration) {
            // generate app key
            Artisan::call('key:generate');

            // redirect to setup
            return redirect('installer/setup');
        }

        // redirect back
        return redirect()->back();
    }

    protected function saveEnv($content)
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
            file_put_contents(base_path(self::ENV), $envContent);
        } catch (Exception $e) {
            return redirect()->back();
        }

        // do nothing after!
    }
}
