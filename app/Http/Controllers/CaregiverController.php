<?php

namespace App\Http\Controllers;

use App\Http\Requests\CaregiverRequest;
use App\Models\Caregiver;
use App\Repositories\Interfaces\CaregiverRepositoryInterface;
use App\Repositories\Interfaces\UserRepositoryInterface;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;

class CaregiverController extends Controller
{


    public function __construct(
        private CaregiverRepositoryInterface $caregiverRepository,
        private UserRepositoryInterface $userRepository
    ) {
    }
    public  function addCaregiver(CaregiverRequest $request)
    {
        try {
            DB::beginTransaction();
            $user = $this->userRepository->addUser([
                'name' => $request->name,
                'email' => $request->email,
                'password' => bcrypt($request->password),
                'type' => 1
            ]);
            $this->caregiverRepository->addCaregiver(
                array(
                    'Role' => $request->Role,
                    'User_id' => $user->id,
                )
            );
            DB::commit();
            $data = caregiverData($user);
            return responseJson(201, $data, 'Caregiver successfully registered');
        } catch (Exception $e) {
            DB::rollback();
            return responseJson(401, "", $e);
        }
    }
    public function getCaregiver($caregiver_id)
    {
        $caregiver = $this->caregiverRepository->getCaregiver($caregiver_id);
        if ($caregiver) {
            $data = caregiverData($caregiver->user);
            return responseJson(201, $data, $caregiver->user->name . " Data");
        }
        return responseJson(401, '', 'this caregiver_id not found');
    }
    
    public function getCaregiverPatients($caregiver_id)
    {
        $caregiver = $this->caregiverRepository->getCaregiver($caregiver_id);
        if ($caregiver) {
            if ($caregiver->patients->count() > 0) {
                $patients = $caregiver->patients()->get();
                foreach ($patients as $patient) {
                    $data[] = patientData($patient->user);
                }
                return responseJson(201, $data, 'All Patient for ' . $caregiver->user->name);
            }
            return responseJson(401, '', 'this caregiver not have Patients');
        }
        return responseJson(401, '', 'this caregiver_id not found');
    }
}
