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

/**
 * Class ApiInstallerController
 * @package Journal\Http\Controllers\API
 */
class ApiInstallerController extends ApiController
{
    /**
     * @var $defaultSettings
     */
    protected $defaultSettings = [
        // default number post per page
        'post_per_page'     => 10,
        // default theme
        'theme'             => 'Casper',
        // default theme template
        'theme_template'    => 'casper',
        // simple pagination
        'simple_pagination' => 1,
        // default date format
        'date_format'       => 'F j, Y',
        // default time format
        'time_format'       => 'g:i a'
    ];

    /**
     * @var $defaultNavigation
     */
    protected $defaultNavigation = [
        'label' => 'Home',
        'url'   => '/',
        'order' => 1
    ];

    /**
     * @var DatabaseManager
     */
    protected $database;
    /**
     * @var EnvironmentManager
     */
    protected $environment;
    /**
     * @var SettingsRepositoryInterface
     */
    protected $settings;
    /**
     * @var UserRepositoryInterface
     */
    protected $users;

    /**
     * ApiInstallerController constructor.
     * @param SettingsRepositoryInterface $settings
     * @param UserRepositoryInterface $users
     * @param DatabaseManager $database
     * @param EnvironmentManager $environment
     */
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

    /**
     * @param Request $request
     * @return mixed
     */
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

        // seed
        $this->database->seed();

        // return the redirect url
        return $this->respond([
            'redirect_url' => url('/installer/setup')
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
        $this->generateJournalSettings($request);

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

    /**
     * @param $data
     * @return array
     */
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

        // insert the default navigation but first, fix it first by setting the
        // url of the link
        $navigation = $this->defaultNavigation;

        // url
        $navigation['url'] = url($navigation['url']);

        // save it
        $results[] = $this->settings->save('navigation', json_encode([$navigation]));

        return $results;
    }
}