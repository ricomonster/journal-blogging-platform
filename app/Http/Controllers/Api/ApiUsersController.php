<?php //-->
namespace Journal\Http\Controllers\Api;

use Journal\Repositories\Users\UserRepositoryInterface;
use Input, Request;

/**
 * Class ApiUsersController
 * @package Journal\Http\Controllers\Api
 */
class ApiUsersController extends ApiController
{
    /**
     * The upload directory
     *
     * @var string
     */
    protected $uploadPath;

    /**
     * The url of the upload directory
     *
     * @var string
     */
    protected $uploadUrl;

    /**
     * The user repository implementation
     *
     * @var UserRepositoryInterface
     */
    protected $users;

    /**
     * Creates a new API User Controller
     *
     * @param UserRepositoryInterface $users
     */
    public function __construct(UserRepositoryInterface $users)
    {
        // set the directory paths
        $this->uploadPath   = public_path('uploads/user');
        $this->uploadUrl    = Request::root().'/uploads/user';

        $this->users = $users;
    }

    /**
     * Returns all active users
     *
     * @return mixed
     */
    public function allUsers()
    {
        return $this->respond([
            'data' => [
                'user' => $this->users->all()]]);
    }

    public function changePassword()
    {
        $id                 = Input::get('id');
        $currentPassword    = Input::get('current_password');
        $newPassword        = Input::get('new_password');
        $repeatPassword     = Input::get('repeat_password');

        $messages = $this->users->validateChangePassword($id, $currentPassword, $newPassword, $repeatPassword);

        // check for errors
        if (count($messages) > 0) {
            // return the error messages
            return $this->setStatusCode(400)
                ->respondWithError($messages);
        }

        // update password
    }

    /**
     * Creates a new user
     *
     * @return mixed
     */
    public function createUser()
    {
        $email      = Input::get('email');
        $password   = Input::get('password');
        $name       = Input::get('name');
        $role       = Input::get('role');

        // validate
        $messages = $this->users->validateCreate($email, $password, $name);

        // check for errors
        if (count($messages) > 0) {
            // return the error messages
            return $this->setStatusCode(400)
                ->respondWithError($messages);
        }

        // check if role is set
        $role = (empty($role)) ? '2' : $role;

        // create user
        $user = $this->users->create($email, $password, $name, $role);

        return $this->respond([
            'data' => [
                'message'   => 'You have successfully added a new user.',
                'user'      => $user->toArray()]]);
    }

    /**
     * Fetches a user using its id
     *
     * @return mixed
     */
    public function get()
    {
        // check if there is an id supplied
        if (empty($id)) {
            // send error message
            return $this->setStatusCode(400)
                ->respondWithError('Please set the user ID.');
        }

        // get user
        $user = $this->users->findById($id);

        return $this->respond([
            'data' => [
                'user' => $user]]);
    }

    /**
     * Update the users account
     *
     * @return mixed
     */
    public function updateAccount()
    {
        $name       = Input::get('name');
        $email      = Input::get('email');
        $biography  = Input::get('biography');
        $slug       = Input::get('slug');
        $website    = Input::get('website');
        $location   = Input::get('location');
        $id         = Input::get('id');

        // validate
        $messages = $this->users->validateUpdate($email, $name, $biography, $website, $location, $slug, $id);

        // check for errors
        if (count($messages) > 0) {
            // return the error messages
            return $this->setStatusCode(400)
                ->respondWithError($messages);
        }

        // update
        $user = $this->users->update($id, $email, $name, $biography, $website, $location, $slug);

        return $this->respond([
            'data' => [
                'message'   => 'You have successfully updated your account',
                'user'      => $user->toArray()]]);
    }

    public function uploadImage()
    {
        $id             = Input::get('id');
        $files          = Input::file('files');
        $settingName    = Input::get('setting_name');
        $imageUrl       = Input::get('image_url');

        // check first if ID is set
        if (empty($id)) {
            return $this->setStatusCode(400)
                ->respondWithError('There is no ID set.');
        }

        // check if setting name is set
        if (empty($settingName)) {
            return $this->setStatusCode(400)
                ->respondWithError('Your request cannot be processed. Please try again later.');
        }

        // check if there's a file
        if (empty($files)) {
            // image is just a url, update the field
            $respond = $this->users->updateImage($settingName, $imageUrl, $id);

            return $this->respond([
                'data' => [
                    'user' => $respond->toArray()]]);
        }

        // upload image
        // get the file name
        $filename = $files->getClientOriginalName();
        // upload
        $files->move($this->uploadPath, $filename);

        // validate if file is uploaded
        if (!Input::hasFile('files')) {
            // send error message
            return $this->setStatusCode(400)
                ->respondWithError($filename.' could not be uploaded. Please try again later');
        }

        // file uploaded is uploaded
        // prep url of the file
        $fileUrl = $this->uploadUrl.'/'.rawurlencode($filename);
        $respond = $this->users->updateImage($settingName, $fileUrl, $id);

        // return url
        return $this->respond([
            'data' => [
                'user' => $respond->toArray()]]);

    }
}
