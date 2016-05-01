<?php //-->
namespace Journal\Repositories\User;

interface UserRepositoryInterface
{
    /**
     * Creates a user
     *
     * @param array $user
     * @return \Journal\User
     */
    public function create($user);

    /**
     * Search a user based on the given parameters
     *
     * @param array $parameters
     * @return \Journal\User
     */
    public function search($parameters);

    /**
     * Gets all the active users.
     *
     * @return \Journal\User
     */
    public function all();

    /**
     * Gets a user based on the saved email.
     *
     * @param string $email
     * @return \Journal\User
     */
    public function findByEmail($email);

    /**
     * Gets a user based on the given ID.
     *
     * @param int $id
     * @return \Journal\User
     */
    public function findById($id);

    /**
     * Finds a user based on the given slug.
     *
     * @param array $slug
     * @return \Journal\User
     */
    public function findBySlug($slug);

    /**
     * Updates the details of the user
     *
     * @param array $user
     * @return \Journal\User
     */
    public function updateDetails($user);

    /**
     * Updates the current password of the user.
     *
     * @param int $id
     * @param string $password
     * @return void
     */
    public function updatePassword($id, $password);

    /**
     *	Sets the user based on the given ID to be inactive
     *
     * @param array $user
     * @return void
     */
    public function setToInactive($user);

    /**
     * Checks if the inputted current password of the user is the same with ones
     * saved in the database.
     *
     * @param int $id
     * @param string $currentPassword
     * @return bool
     */
    public function checkUserCurrentPassword($id, $currentPassword);

    /**
     * Generates a slug based on the given string. It also checks if there are
     * duplicates of the given string converted to slug in the database then
     * appends the number of instances of the slug to the newly created
     * slug.
     *
     * @param string $string
     * @param int $id
     * @return string
     */
    public function generateSlug($string, $id);

    /**
     * Validate the user passwords.
     *
     * @param array $passwords
     * @return \Illuminate\Support\MessageBag
     */
    public function validatePasswords($passwords);

    /**
     * Validate the user creation and update.
     *
     * @param array $user
     * @return \Illuminate\Support\MessageBag
     */
    public function validateUser($user);
}
