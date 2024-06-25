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
}

?>