<?php
namespace Services;

use Repositories\UserRepository;

class UserService
{

    private $repository;

    function __construct()
    {
        $this->repository = new UserRepository();
    }

    public function checkUsernamePassword($username, $password)
    {
        return $this->repository->checkUsernamePassword($username, $password);
    }
    public function createNewUser($user)
    {
        return $this->repository->createNewUser($user);
    }

    public function checkUsernameExists($username)
    {
        return $this->repository->checkUsernameExists($username);
    }

    public function checkEmailExists($email)
    {
        return $this->repository->checkEmailExists($email);
    }

    public function updateUser($user, $username)
    {
        return $this->repository->updateUser($user, $username);
    }


    public function updateUserPassword($password, $id)
    {
        return $this->repository->changePassword($password, $id);
    }
}

?>