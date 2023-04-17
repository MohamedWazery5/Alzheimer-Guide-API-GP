<?php
namespace App\Repositories\Interfaces;

interface CaregiverRepositoryInterface{
    public function addCaregiver(array $data);
    public function getCaregiver($id);
}
