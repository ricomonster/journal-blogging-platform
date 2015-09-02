<?php //-->
namespace Journal\Http\Controllers\Api;

use Illuminate\Http\Request;
use Journal\Http\Requests;
use Journal\Repositories\Post\PostRepositoryInterface;
use Journal\Repositories\Setting\SettingRepositoryInterface;
use Journal\Repositories\User\UserRepositoryInterface;
use JWTAuth;

class ApiInstallerController extends ApiController
{
    protected $posts;
    protected $settings;
    protected $users;

    public function __construct(PostRepositoryInterface $posts, SettingRepositoryInterface $settings, UserRepositoryInterface $users)
    {
        $this->middleware('installation.installed');

        $this->posts    = $posts;
        $this->settings = $settings;
        $this->users    = $users;
    }

    public function createAccount(Request $request)
    {
        // validate first
        $messages = $this->users->validateUserCreate($request->all());

        // check if there are errors
        if (count($messages) > 0) {
            return $this->setStatusCode(self::BAD_REQUEST)
                ->respondWithError($messages);
        }

        // create the user
        $user = $this->users->create(
            $request->input('name'),
            $request->input('email'),
            $request->input('password'));

        // create the blog details of the user
        $title = (empty($request->input('title'))) ? 'Journal' : $request->input('title');
        $this->settings->save('title', $title);

        // install this thang
        $this->settings->save('installed', 'true');

        // TODO: Create first post
        $this->createFirstPost($user);

        // generate token
        $token = JWTAuth::fromUser($user);

        // return response
        return $this->respond([
            'settings'  => $this->settings->get('title'),
            'token'     => $token,
            'user'      => $user->toArray()]);
    }

    protected function createFirstPost($user)
    {
        // get readme file
        $content = file_get_contents(base_path('readme.md'));

        // create the post
        $this->posts->create(
            $user->id,
            'Journal Blogging Platform',
            $content,
            'journal-blogging-platform',
            1,
            strtotime(date('Y-m-d h:i:s')),
            []);
    }
}
