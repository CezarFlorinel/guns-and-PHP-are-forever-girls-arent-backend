<?php
namespace Models;

class User implements \JsonSerializable
{
    public int $userId;
    public string $password;
    public string $username;
    public string $email;
    public int $avatarId;
    public bool $admin = false; // default is false, there is only one admin, the big boss

    public function jsonSerialize(): mixed
    {
        return get_object_vars($this);
    }

}
