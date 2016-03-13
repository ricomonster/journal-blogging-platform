<?php //-->
namespace Journal\Http\Controllers\API;

use Illuminate\Http\Request;
use Journal\Http\Controllers\API\ApiController;
use Journal\Http\Requests;
use Journal\Repositories\Settings\SettingsRepositoryInterface;
use Journal\Repositories\User\UserRepositoryInterface;
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

    protected $settings;
    protected $users;

    public function __construct(SettingsRepositoryInterface $settings, UserRepositoryInterface $users)
    {
        $this->settings = $settings;
        $this->users    = $users;
    }

    public function saveSetup(Request $request)
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

    protected function installJournal()
    {
        file_put_contents(storage_path('installed'), '');
    }

    protected function generateJournalSettings($data)
    {
        $results = [];

        // prepare the settings
        $settings = [
            'blog_title' => $data->blog_title,
            'blog_description' => $data->blog_description
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
