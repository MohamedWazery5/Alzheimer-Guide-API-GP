<?php

namespace App\Repositories\Interfaces;

use App\Models\Patient;

interface MemoryRepositoryInterface
{
    public function addMemory(array $data);
    public function getMemory($memory_id);
    public function updateMemory($memory_id,array $data);
    public function deleteMemory($memory_id);
    public function getMemories($patient_id);

}
