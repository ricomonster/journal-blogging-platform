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
    protected $posts;
    protected $settings;
    protected $users;

    public function __construct(PostRepositoryInterface $posts, SettingRepositoryInterface $settings,UserRepositoryInterface $users)
    {
        $this->posts = $posts;
        $this->settings = $settings;
        $this->users = $users;
    }

    public function account()
    {
        return view('installer.account');
    }

    public function createAccount(CreateAccountRequest $request)
    {
        $name       = Input::get('name');
        $email      = Input::get('email');
        $password   = Input::get('password');

        // create user
        $this->users->create($email, $password, $name, 1);

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
        $migrate = Artisan::call('migrate', ['--force' => true]);

        // check if migration is successful
        if (!$migrate) {
            // redirect
            return redirect('installer/account');
        }

        return redirect('installer')
            ->with('message', 'There is something wrong. Please check your database configuration to continue.');
    }

    public function setupBlog(SetupBlogRequest $request)
    {
        $blogTitle          = Input::get('blog_title');
        $blogDescription    = Input::get('blog_description');
        $theme              = Input::get('theme');

        // create settings
        $this->settings->set('blog_title', $blogTitle);
        $this->settings->set('blog_description', $blogDescription);
        $this->settings->set('theme', $theme);
        $this->settings->set('theme_name', ucfirst($theme));
        $this->settings->set('installed', 'true');

        // get first user
        $user = User::orderBy('id', 'desc')->first();

        // create first post
        // open readme
        $content = file_get_contents(base_path('readme.md'));
        $slug = $this->posts->createSlug('Laravel 5', null);

        $this->posts->create($user->id, 'Laravel 5', $content, $slug, 1, date('Y-m-d h:i:s'), []);

        // log in the current user
        Auth::loginUsingId($user->id);
        // redirect
        return redirect('journal');
    }
}
