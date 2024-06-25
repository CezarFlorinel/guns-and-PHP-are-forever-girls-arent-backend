<?php

namespace Controllers;

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

        $postedUser = $this->createObjectFromPostedJson("Models\\User");

        $user = $this->service->checkUsernamePassword($postedUser->username, $postedUser->password);

        if (!$user) {
            $this->respondWithError(401, "Invalid username or password");
            return;
        }

        $tokenResponse = $this->generateJwt($user);

        $this->respond($tokenResponse);
    }

    public function createUser()
    {
        $postedUser = $this->createObjectFromPostedJson("Models\\User");

        $this->runChecks($postedUser);

        $user = $this->service->createNewUser($postedUser);

        if (!$user) {
            $this->respondWithError(400, "User could not be created");
            return;
        }

        $this->respond($user);
    }

    private function runChecks($postedUser)
    {
        // check if the user already exists
        if ($this->service->checkEmailExists($postedUser->email)) {
            $this->respondWithError(400, "Email already exists");
            return;
        } else if ($this->service->checkUsernameExists($postedUser->username)) {
            $this->respondWithError(400, "Username already exists");
            return;
        } else if (strlen($postedUser->password) < 8) {
            $this->respondWithError(400, "Password must be at least 8 characters long");
            return;
        } else if ($postedUser->avatarId <= 0 || $postedUser->avatarId > 5) {
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
}
