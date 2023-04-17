<?php

namespace App\Repositories;

use App\Models\Caregiver;
use App\Repositories\Interfaces\CaregiverRepositoryInterface;

class CaregiverRepository implements CaregiverRepositoryInterface
{
    public function __construct(private Caregiver $caregiver)
    {
    }
    public function addCaregiver(array $data)
    {
        return $this->caregiver->create($data);
    }
    public function getCaregiver($id){
        return $this->caregiver->find($id);
    }

}
