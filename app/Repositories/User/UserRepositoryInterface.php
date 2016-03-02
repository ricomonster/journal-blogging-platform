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
     * @return [type] [description]
     */
    public function search($parameters);

    /**
     * [findByEmail description]
     * @param  [type] $email [description]
     * @return [type]        [description]
     */
    public function findByEmail($email);

    /**
     * [findById description]
     * @param  [type] $id [description]
     * @return [type]     [description]
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
