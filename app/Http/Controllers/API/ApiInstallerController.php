<?php //-->
namespace Journal\Http\Controllers\API;

use Illuminate\Http\Request;
use Journal\Http\Controllers\API\ApiController;
use Journal\Http\Requests;
use Journal\Repositories\Settings\SettingsRepositoryInterface;
use Journal\Repositories\User\UserRepositoryInterface;
use Journal\Support\DatabaseManager;
use Journal\Support\EnvironmentManager;
use Validator;

class ApiInstallerController extends ApiController
{
    protected $defaultSettings = [
        'post_per_page'     => 10,
        'theme'             => 'Casper',
        'theme_template'    => 'casper',
        'date_format'       => 'F j, Y',
        'time_format'       => 'g:i a'
    ];

    protected $database;
    protected $environment;
    protected $settings;
    protected $users;

    public function __construct(
        SettingsRepositoryInterface $settings,
        UserRepositoryInterface $users,
        DatabaseManager $database,
        EnvironmentManager $environment)
    {
        $this->settings = $settings;
        $this->users    = $users;

        $this->database     = $database;
        $this->environment  = $environment;
    }

    public function database(Request $request)
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

        // update the contents to the env file
        $this->environment->update($env);

        // perform migration
        $result = $this->database->migrate();

        // there's an error
        if ($result) {
            // else show error message
            return $this->setStatusCode(self::INTERNAL_SERVER_ERROR)
                ->respondWithError([
                    'message' => 'Something went wrong while performing migration.']);
        }

        // return the redirect url
        return $this->respond([
            'redirect_url' => '/installer/setup'
        ]);
    }

    /**
     * Saves the initial setup of the application.
     *
     * @param Request $request
     * @return mixed
     */
    public function setup(Request $request)
    {
        // validate first
        $error = $this->users->validateUser($request->all());

        if (count($error) > 0) {
            return $this->setStatusCode(self::BAD_REQUEST)
                ->respondWithError($error);
        }

        // once there's no error save the user, populate the settings and
        // create a dummy post
        $user = $this->users->create($request);

        // generate some default settings
        $settings = $this->generateJournalSettings($request);

        // finally, installation is complete
        $this->installJournal();

        // TODO: create first post for the user

        // return user details
        return $this->respond(['user' => $user->toArray()]);
    }

    /**
     * "Install" Journal by putting a file somewhere in the storage path
     */
    protected function installJournal()
    {
        file_put_contents(storage_path('installed'), '');
    }

    protected function generateJournalSettings($data)
    {
        $results = [];

        // prepare the settings
        $settings = [
            'blog_title'        => $data->blog_title,
            'blog_description'  => $data->blog_description
        ];

        // get the other settings and merge it
        $settings = array_merge($settings, $this->defaultSettings);

        // loop
        foreach ($settings as $key => $value) {
            $results[] = $this->settings->save($key, $value);
        }

        return $results;
    }
}
