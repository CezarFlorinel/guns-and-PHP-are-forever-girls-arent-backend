<?php

namespace Repositories;

use PDO;
use PDOException;
use Repositories\Repository;
use Models\User;

class UserRepository extends Repository
{
    function checkUsernamePassword($username, $password)
    {
        try {
            // retrieve the user with the given username
            $stmt = $this->connection->prepare("SELECT * FROM Users WHERE username = :username");
            $stmt->bindParam(':username', $username);
            $stmt->execute();

            $stmt->setFetchMode(PDO::FETCH_CLASS, 'Models\\User');
            $user = $stmt->fetch();

            // verify if the password matches the hash in the database
            $result = $this->verifyPassword($password, $user->password);

            if (!$result)
                return false;

            // do not pass the password hash to the caller
            $user->password = "";

            return $user;
        } catch (PDOException $e) {
            echo $e;
        }
    }

    public function createNewUser(User $user): ?User
    {
        try {
            $isAdmin = 0; // default value for admin

            $stmt = $this->connection->prepare("INSERT INTO Users (username, password, email, avatarId, admin) VALUES (?, ?, ?, ?, ?)");

            // bind parameters, used in the sql code above
            $username = $user->username;
            $password = $this->hashPassword($user->password);
            $email = $user->email;
            $avatarId = $user->avatarId;
            $stmt->execute([$username, $password, $email, $avatarId, $isAdmin]);

            // if the row count is greater than 0, then the user was created
            if ($stmt->rowCount() > 0) {
                $lastInsertId = $this->connection->lastInsertId(); // returns the last inserted id, used to auto increment the user id
                return $this->returnUserById($lastInsertId);
            }
        } catch (PDOException $e) {
            echo $e;
        }

        return null;
    }

    function checkUsernameExists($enteredUsername): bool
    {
        $stmt = $this->connection->prepare("SELECT COUNT(*) as user_count FROM Users WHERE username = ?");
        $stmt->execute([$enteredUsername]);
        $result = $stmt->fetch(PDO::FETCH_ASSOC);
        return ($result['user_count'] > 0);
    }
    public function checkEmailExists($enteredEmail): bool
    {
        $stmt = $this->connection->prepare("SELECT COUNT(*) as user_count FROM Users WHERE email = ?");
        $stmt->execute([$enteredEmail]);
        $result = $stmt->fetch(PDO::FETCH_ASSOC);
        return ($result['user_count'] > 0);
    }

    public function returnUserById($userId): ?User
    {
        try {
            $stmt = $this->connection->prepare("SELECT * FROM Users WHERE userId = ?");
            $stmt->setFetchMode(PDO::FETCH_CLASS, 'Models\\User');
            $stmt->execute([$userId]);
            $user = $stmt->fetch(PDO::FETCH_CLASS);
            return ($user !== false) ? $user : null;
        } catch (PDOException $e) {
            echo $e;
        }
    }

    public function updateUser(User $user, $username): ?User
    {
        try {

            try {
                $userCurrent = $this->checkUsernamePassword($username, $user->password);
                if (!$userCurrent) {
                    return null;
                }
            } catch (PDOException $e) {
                echo $e;
            }

            $stmt = $this->connection->prepare("UPDATE Users SET username = ?, email = ?, avatarId = ? WHERE username = ?");
            $stmt->execute([$user->username, $user->email, $user->avatarId, $username]);

            return $this->returnUserById($userCurrent->userId);

        } catch (PDOException $e) {
            echo $e;
        }
        return null;
    }

    public function changePassword($userId, $newPassword): bool
    {
        try {
            $stmt = $this->connection->prepare("UPDATE Users SET password = ? WHERE userId = ?");
            $stmt->execute([$this->hashPassword($newPassword), $userId]);
            return true;
        } catch (PDOException $e) {
            echo $e;
        }
        return false;
    }

    // hash the password 
    function hashPassword($password)
    {
        return password_hash($password, PASSWORD_DEFAULT);
    }

    // verify the password hash
    function verifyPassword($input, $hash)
    {
        return password_verify($input, $hash);
    }
}
