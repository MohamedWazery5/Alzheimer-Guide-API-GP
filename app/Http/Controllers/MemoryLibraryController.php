<?php

namespace App\Http\Controllers;

use App\Http\Requests\MemoryRequest;
use App\Models\MemoryLibrary;
use App\Models\Patient;
use App\Repositories\Interfaces\MemoryRepositoryInterface;
use App\Traits\ManageFileTrait;
use Carbon\Carbon;

class MemoryLibraryController extends Controller
{
    use ManageFileTrait;
    public function __construct(private MemoryRepositoryInterface $memory)
    {
    }
    public function addMemory(MemoryRequest $request)
    {
        $photo = $this->uploadFile($request, 'photo', 'memoriesPhotos');
        $memory = $this->memory->addMemory([
            'name' => $request->name,
            'description' => $request->description,
            'photo' => $photo,
            'type' => $request->type,
            'patient_id' => $request->patient_id
        ]);
        $data = MemoryData($memory);
        return responseJson(201, $data, "memory Insertes ");
    }

    public function getMemory($memory_id)
    {
        $memory = $this->memory->getMemory($memory_id);
        if ($memory) {
            $data = MemoryData($memory);
            return responseJson(201, $data, "memory data");
        }
        return responseJson(401, '', 'this memory_id not found');
    }
    public function getMemories($patient_id)
    {
        $memories = $this->memory->getMemories($patient_id);
        if ($memories->count()>0) {
            foreach ($memories as $memory) {
                $data[] = MemoryData($memory);
            }
            return responseJson(201, $data, 'memories data');
        }
        return responseJson(401, '', 'this Patient Not have Any Memories');
    }
    public function deleteMemory($memory_id)
    {
        $memory = $this->memory->getMemory($memory_id);
        if ($memory) {
            $this->deleteFile($memory->photo);
            $this->memory->deleteMemory($memory_id);
            return responseJson(201, '', ' Memory Deleted');
        }
        return responseJson(401, '', 'this memory_id not found');
    }

    public function updateMemory(MemoryRequest $request, $memory_id)
    {
        $memory = $this->memory->getMemory($memory_id);
        if ($memory) {
            $photo = $this->uploadFile($request, 'photo', 'memoriesPhotos');
            if ($photo!="Null") {
                $this->deleteFile($memory->photo);
            } else {
                $photo = $memory->photo;
            }
            $this->memory->updateMemory($memory_id,[
                'name' => $request->name,
                'description' => $request->description,
                'type' => $request->type,
                'photo' => $photo,
                'updated_at' => Carbon::now()
            ]);
            $data = MemoryData($memory);
            return responseJson(201, $data, 'Memory Updated');
        }
        return responseJson(401, '', 'this memory_id not found');
    }
    public function getMemoryImage($memory_id)
    {
        $memory = MemoryLibrary::find($memory_id);
        return $this->getFile($memory->photo);
    }
}
