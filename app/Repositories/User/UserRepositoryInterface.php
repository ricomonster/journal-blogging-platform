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
     * @param $email
     * @return \Journal\User
     */
    public function findByEmail($email);

    /**
     * Gets a user based on the given ID.
     *
     * @param $id
     * @return \Journal\User
     */
    public function findById($id);

    /**
     * [findBySlug description]
     * @param  [type] $slug [description]
     * @return [type]       [description]
     */
    public function findBySlug($slug);

    /**
     * [updateDetails description]
     * @param  [type] $user [description]
     * @return [type]       [description]
     */
    public function updateDetails($user);

    /**
     * [updatePassword description]
     * @param  [type] $passwords [description]
     * @return [type]            [description]
     */
    public function updatePassword($passwords);

    /**
     * [setToInactive description]
     * @param [type] $user [description]
     */
    public function setToInactive($user);

    /**
     * Validate the user creation and update.
     *
     * @param Request $user
     * @return \Illuminate\Support\MessageBag
     */
    public function validateUser($user);
}
