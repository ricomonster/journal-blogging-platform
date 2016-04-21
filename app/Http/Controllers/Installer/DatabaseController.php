<?php //-->
namespace Journal\Http\Controllers\Installer;

use Illuminate\Http\Request;
use Journal\Http\Requests;
use Journal\Http\Controllers\Controller;
use Journal\Support\EnvironmentManager;

class DatabaseController extends Controller
{
    protected $environment;

    public function __construct(EnvironmentManager $environment)
    {
        $this->environment = $environment;
    }

    public function page()
    {
        // let's get the contents of the .env or .env.example
        $env = $this->environment->getEnv(true);

        return view('installer.database', compact('env'));
    }
}
