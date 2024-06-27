<?php

namespace Controllers;

use Exception;
use Services\UserService;


class UserController extends Controller
{
    private $service;

    // initialize services
    function __construct()
    {
        $this->service = new UserService();
    }

    public function login()
    {
        try {

            $postedUser = $this->createObjectFromPostedJson("Models\\User");

            $user = $this->service->checkUsernamePassword($postedUser->username, $postedUser->password);

            if (!$user) {
                $this->respondWithError(401, "Invalid username or password");
                return;
            }

            $tokenResponse = $this->generateJwt($user);

            $this->respond($tokenResponse);
        } catch (Exception $e) {
            $this->respondWithError(500, $e->getMessage());
        }
    }

    public function createUser()
    {
        try {
            $postedUser = $this->createObjectFromPostedJson("Models\\User");

            $this->runChecks($postedUser);
            $this->runCheckPassword($postedUser->password);
            $this->runCheckForExistingUser($postedUser);

            $user = $this->service->createNewUser($postedUser);


            if (!$user) {
                $this->respondWithError(400, "User could not be created");
                return;
            }

            $this->respond($user);
        } catch (Exception $e) {
            $this->respondWithError(500, $e->getMessage());
        }
    }
    public function updateUser($username)
    {
        try {
            $postedUser = $this->createObjectFromPostedJson("Models\\User");

            $this->runChecks($postedUser);
            $user = $this->service->updateUser($postedUser, $username);

            if (!$user) {
                $this->respondWithError(400, "User could not be updated");
                return;
            }

            $this->respond($user);
        } catch (Exception $e) {
            $this->respondWithError(500, $e->getMessage());
        }
    }

    public function updatePassword()
    {
        try {
            $json = file_get_contents('php://input');
            $data = json_decode($json);
            $newPassword = $data->newPassword;
            $username = $data->username;
            $password = $data->password;

            $this->runCheckPassword($newPassword);

            if (isset($newPassword) && isset($username) && isset($password)) {
                if ($this->service->updateUserPassword($newPassword, $username, $password)) {
                    http_response_code(200);
                } else {
                    $this->respondWithError(400, "Somethign went wrong, please try again.");
                }

            } else {
                $this->respondWithError(400, "Missing password or username");
            }
        } catch (Exception $e) {
            $this->respondWithError(500, $e->getMessage());
        }
    }

    private function runChecks($postedUser)
    {
        if ($postedUser->avatarId <= 0 || $postedUser->avatarId > 5) {
            $this->respondWithError(400, "Avatar invalid");
            return;
        } else if (strlen($postedUser->username) < 3) {
            $this->respondWithError(400, "Username must be at least 3 characters long");
            return;
        } else if (strlen($postedUser->email) < 6) {
            $this->respondWithError(400, "Email must be at least 3 characters long");
            return;
        }
    }

    private function runCheckForExistingUser($postedUser)
    {
        if ($this->service->checkEmailExists($postedUser->email)) {
            $this->respondWithError(400, "Email already exists");
            return;
        } else if ($this->service->checkUsernameExists($postedUser->username)) {
            $this->respondWithError(400, "Username already exists");
            return;
        }

    }

    private function runCheckPassword($password)
    {
        if (strlen($password) < 8) {
            $this->respondWithError(400, "Password must be at least 8 characters long");
            return;
        }
    }
}
