<?php //-->
namespace Journal\Http\Controllers\Api;

use Illuminate\Http\Request;
use Journal\Http\Requests;
use Journal\Repositories\Post\PostRepositoryInterface;
use Journal\Repositories\Setting\SettingRepositoryInterface;
use Journal\Repositories\Tag\TagRepositoryInterface;
use Journal\Repositories\User\UserRepositoryInterface;
use Artisan;
use JWTAuth;
use Schema;

class ApiInstallerController extends ApiController
{
    protected $posts;
    protected $settings;
    protected $tags;
    protected $users;

    public function __construct(PostRepositoryInterface $posts, SettingRepositoryInterface $settings, TagRepositoryInterface $tags, UserRepositoryInterface $users)
    {
        $this->middleware('installation.installed', ['only' => ['createAccount']]);

        $this->posts    = $posts;
        $this->settings = $settings;
        $this->tags     = $tags;
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

        // let's assume this setup has a Casper theme installed
        $this->settings->save('theme', 'casper');

        // install this thang
        $this->settings->save('installed', 'true');

        // Create first post
        $this->createFirstPost($user);

        // generate token
        $token = JWTAuth::fromUser($user);

        // return response
        return $this->respond([
            'token'     => $token,
            'user'      => $user->toArray()]);
    }

    public function install()
    {
        // check if the settings table exists
        $tableExists = Schema::hasTable('settings');

        if (!$tableExists) {
            Artisan::call('migrate');
        }

        return $this->respond([
            'installed' => true]);
    }

    protected function createFirstPost($user)
    {
        // get readme file
        $content = file_get_contents(base_path('readme.md'));

        $tagIds = $this->generateFirstPostTags();

        // create the post
        $post = $this->posts->create(
            $user->id,
            'Journal Blogging Platform',
            $content,
            '',
            'journal-blogging-platform',
            1,
            strtotime(date('Y-m-d h:i:s')),
            $tagIds);

        return $post;
    }

    protected function generateFirstPostTags()
    {
        $tags = ['Journal', 'Blogging Platform', 'Laravel', 'Angular JS'];
        $tagIds = [];

        // check if there are tags
        if ($tags) {
            // loop
            foreach ($tags as $key => $tag) {

                $result = $this->tags->create($tag);
                // get the ID and push it to the array
                array_push($tagIds, $result->id);
            }
        }

        return $tagIds;
    }
}
