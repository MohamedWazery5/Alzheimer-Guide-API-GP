<?php

namespace App\Repositories;

use App\Models\MemoryLibrary;
use App\Repositories\Interfaces\MemoryRepositoryInterface;

class MemoryRepository implements MemoryRepositoryInterface
{
    public function __construct(private MemoryLibrary $memory)
    {
    }
    public function addMemory(array $data)
    {
        return $this->memory->create($data);
    }
    public function getMemory($memory_id)
    {
        return $this->memory->find($memory_id);
    }
    public function updateMemory($memory_id, array $data)
    {
        return $this->memory->whereId($memory_id)->update( $data);
    }
    public function deleteMemory($memory_id)
    {
        return $this->memory->destroy($memory_id);
    }
    public function getMemories($patient_id)
    {
        return $this->memory->where('patient_id',$patient_id)->get();
    }
}
