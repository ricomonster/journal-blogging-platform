<?php //-->
namespace Journal\Http\Controllers;

use Journal\Http\Requests\CreateAccountRequest;
use Journal\Http\Requests\SetupBlogRequest;
use Journal\Repositories\Posts\PostRepositoryInterface;
use Journal\Repositories\Settings\SettingRepositoryInterface;
use Journal\Repositories\Users\UserRepositoryInterface;
use Journal\User;
use Artisan, Auth, Input;

class InstallerController extends Controller
{
    public function account()
    {
        return view('installer.account');
    }

    public function createAccount(CreateAccountRequest $request, UserRepositoryInterface $userRepository)
    {
        $name       = Input::get('name');
        $email      = Input::get('email');
        $password   = Input::get('password');

        // create user
        $userRepository->create($email, $password, $name, 1);

        // redirect to next page
        return redirect('installer/blog');
    }

    public function blog()
    {
        return view('installer.blog', [
            'themes' => $this->themes()]);
    }

    public function index()
    {
        return view('installer.index');
    }

    public function setup()
    {
        // run artisan commands
        Artisan::call('key:generate');
        // run artisan migrate
        $migrate = Artisan::call('migrate');

        // check if migration is successfull
        if (!$migrate) {
            // redirect
            return redirect('installer/account');
        }

        return redirect('installer')
            ->with('message', 'There is something wrong. Please check your database configuration to continue.');
    }

    public function setupBlog(SetupBlogRequest $request, PostRepositoryInterface $postRepository, SettingRepositoryInterface $settingsRepository)
    {
        $blogTitle          = Input::get('blog_title');
        $blogDescription    = Input::get('blog_description');
        $theme              = Input::get('theme');

        // create settings
        $settingsRepository->create('blog_title', $blogTitle);
        $settingsRepository->create('blog_description', $blogDescription);
        $settingsRepository->create('theme', $theme);
        $settingsRepository->create('theme_name', ucfirst($theme));
        $settingsRepository->create('installed', 'true');

        // get first user
        $user = User::orderBy('id', 'desc')->first();

        // create first post
        // open readme
        $content = file_get_contents(base_path('readme.md'));
        $slug = $postRepository->createSlug('Laravel 5', null);

        $postRepository->create($user->id, 'Laravel 5', $content, $slug, 1, date('Y-m-d h:i:s'), []);

        // log in the current user
        Auth::loginUsingId($user->id);
        // redirect
        return redirect('journal');
    }
}
