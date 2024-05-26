<?php
namespace Models;

class User implements \JsonSerializable
{ // make them private and use getters and setters
    //user data is more sensible, so i need to make it private
    // in order to avoid deprecated error, use the following syntax, it must MATCH THE SAME NAME COLUMN AS IN THE DATABASE, AHHHH
    private int $userId;
    private string $password;
    private string $username;
    private string $email;
    private int $avatarId;
    private bool $admin = false; // default is false, there is only one admin, the big boss

    public function jsonSerialize(): mixed
    {
        return get_object_vars($this);
    }

    // getters
    public function getUserId(): int
    {
        return $this->userId;
    }

    public function getPassword(): string
    {
        return $this->password;
    }
    public function getUsername(): string
    {
        return $this->username;
    }
    public function getEmail(): string
    {
        return $this->email;
    }
    public function getAvatarId(): int
    {
        return $this->avatarId;
    }

    public function getIsAdmin(): bool
    {
        return $this->admin;
    }

    // setters

    public function setUserId(int $userId): void
    {
        $this->userId = $userId;
    }

    public function setIsAdmin(bool $isAdmin): void
    {
        $this->admin = $isAdmin;
    }
    public function setUsername(string $username)
    {
        $this->username = $username;
    }
    public function setPassword(string $password)
    {
        $this->password = $password;
    }
    public function setEmail(string $email)
    {
        $this->email = $email;
    }
    public function setAvatarId(int $avatarId)
    {
        $this->avatarId = $avatarId;
    }

}
