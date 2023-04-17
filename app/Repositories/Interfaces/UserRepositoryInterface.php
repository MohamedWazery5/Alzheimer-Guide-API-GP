<?php

namespace App\Repositories\Interfaces;

interface UserRepositoryInterface
{
    public function addUser(array $data);
    public function updateUser($id,array $data);
    public function deleteUser($id);

}
