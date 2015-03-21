<?php //-->
namespace Journal\Repositories\Users;

/**
 * Interface UserRepositoryInterface
 * @package Journal\Core\Repositories\Users
 */
interface UserRepositoryInterface
{
    /**
     * Creates a new user
     *
     * @param  string $email
     * @param  string $password
     * @param  string $name
     * @param  int $role
     * @return User
     */
    public function create($email, $password, $name, $role);

    /**
     * Returns all active user
     *
     * @return User
     */
    public function all();

    /**
     * Fetches a user by its id
     *
     * @param int $id
     * @return User
     */
    public function findById($id);

    /**
     * Fetches a user by its email
     * @param string $email
     * @return User
     */
    public function findByEmail($email);

    /**
     * Fetch a user using its slug
     *
     * @param $slug
     * @return User
     */
    public function findBySlug($slug);

    /**
     * Updates user details
     *
     * @param  int $id
     * @param  string $email
     * @param  string $name
     * @param  string $biography
     * @param  string $website
     * @param  string $location
     * @param  string $slug
     * @return User
     */
    public function update($id, $email, $name, $biography, $website, $location, $slug);

    /**
     * Saves a user image either cover or avatar
     *
     * @param $target
     * @param $url
     * @param $id
     * @return User
     */
    public function updateImage($target, $url, $id);

    /**
     * Updates the users password
     *
     * @param $id
     * @param $password
     * @return User
     */
    public function updatePassword($id, $password);

    /**
     * Sets a user to be inactive
     *
     * @param int $id
     * @return void
     */
    public function setInactive($id);

    /**
     * Logs in the user to the platform
     *
     * @param  string $email
     * @param  string $password
     * @param  bool   $apiRequest
     * @return bool
     */
    public function login($email, $password, $apiRequest);

    /**
     * Logs out the to the platform
     *
     * @return void
     */
    public function logout();

    /**
     * Validates user details for creation
     *
     * @param string $email
     * @param string $password
     * @param string $name
     * @return \Illuminate\Support\MessageBag
     */
    public function validateCreate($email, $password, $name);

    /**
     * Validates if data is valid for update
     *
     * @param string $email
     * @param string $name
     * @param string $biography
     * @param string $website
     * @param string $location
     * @param string $slug
     * @param int $id
     * @return \Illuminate\Support\MessageBag
     */
    public function validateUpdate($email, $name, $biography, $website, $location, $slug, $id);

    /**
     * Validates if passwords are valid
     *
     * @param int $id
     * @param string $currentPassword
     * @param string $newPassword
     * @param string $confirmNewPassword
     * @return \Illuminate\Support\MessageBag
     */
    public function validateChangePassword($id, $currentPassword, $newPassword, $confirmNewPassword);

    /**
     * Checks if slug exists and generates a slugified string
     *
     * @param string $slug
     * @param int $id
     * @return bool|string
     */
    public function validateSlug($slug, $id);
}
