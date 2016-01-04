<?php //-->
namespace Journal\Repositories\User;

/**
 * Interface UserRepositoryInterface
 * @package Journal\Repositories\User
 */
interface UserRepositoryInterface
{
    /**
     * @param $name
     * @param $email
     * @param $password
     * @param $role
     * @return \Journal\User
     */
    public function create($name, $email, $password, $role);

    /**
     * @param $id
     * @param $time
     * @return \Journal\User
     */
    public function timeLogin($id, $time);

    /**
     * @return \Journal\User
     */
    public function all();

    /**
     * @param $id
     * @return \Journal\User
     */
    public function findById($id);

    /**
     * @param $email
     * @return \Journal\User
     */
    public function findByEmail($email);

    /**
     * @param $slug
     * @return \Journal\User
     */
    public function findBySlug($slug);

    /**
     * @param $id
     * @param $newPassword
     * @return \Journal\User
     */
    public function changePassword($id, $newPassword);

    /**
     * @param $id
     * @param $name
     * @param $email
     * @param $biography
     * @param $location
     * @param $website
     * @param $avatarUrl
     * @param $coverUrl
     * @return \Journal\User
     */
    public function updateDetails($id, $name, $email, $biography, $location, $website, $avatarUrl, $coverUrl);

    /**
     * @param $id
     * @return void
     */
    public function setToInactive($id);

    /**
     * @param $data
     * @return \Illuminate\Support\MessageBag
     */
    public function validateUserCreate($data);

    /**
     * @param $data
     * @param $id
     * @return \Illuminate\Support\MessageBag
     */
    public function validateUserUpdate($data, $id);

    /**
     * @param $data
     * @return \Illuminate\Support\MessageBag
     */
    public function validateChangePassword($data);

    /**
     * @param $id
     * @param $password
     * @return boolean
     */
    public function validateUserPassword($id, $password);
}
