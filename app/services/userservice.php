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

    public function updateUserPassword($newPassword, $username, $currentPassword): bool
    {
        return $this->repository->changePassword($newPassword, $username, $currentPassword);
    }

    public function getUserById($userId)
    {
        return $this->repository->returnUserById($userId);
    }


    public function deleteUser($userId)
    {
        return $this->repository->deleteUser($userId);
    }

    public function getAllUsers()
    {
        return $this->repository->returnAllUsers();
    }
}

?>