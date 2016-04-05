<?php //-->
namespace Journal\Http\Controllers\Installer;

use Illuminate\Http\Request;
use Journal\Http\Requests;
use Journal\Http\Controllers\Controller;
use Journal\Support\DatabaseManager;
use Journal\Support\EnvironmentManager;

class DatabaseController extends Controller
{
    protected $database;
    protected $environment;

    public function __construct(DatabaseManager $database, EnvironmentManager $environment)
    {
        $this->database = $database;
        $this->environment = $environment;
    }

    public function page()
    {
        // just like wordpress, show a form so that the user will input
        // the database credentials and we either let the user manually
        // input the credentials in the .env file or we do it.
        // let's get the contents of the .env or .env.example
        $env = $this->environment->getEnv(true);

        return view('installer.database', compact('env'));
    }

    public function setup(Request $request)
    {
        // get the content of the form and apply it to the env file
        $post = $request->all();

        // get the env content
        $env = $this->environment->getEnv(true);

        // loop the post
        foreach ($post as $key => $value) {
            // update the env
            $env[$key] = $value;
        }

        // save the contents to the env file
        $this->saveEnv($env);

        return $this->performMigration();
    }
}
