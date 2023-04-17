<?php

namespace App\Http\Controllers;

use App\Http\Requests\PatientRequest;
use App\Models\Caregiver;
use App\Models\Patient;
use App\Models\User;
use App\Repositories\Interfaces\CaregiverRepositoryInterface;
use App\Repositories\Interfaces\PatientRepositoryInterface;
use App\Repositories\Interfaces\UserRepositoryInterface;
use App\Traits\ManageFileTrait;
use Carbon\Carbon;
use Exception;
use Illuminate\Support\Facades\DB;

class PatientController extends Controller
{
    use ManageFileTrait;
    public function __construct(
        private PatientRepositoryInterface $patientRepository,
        private  UserRepositoryInterface $userRepository
    ) {
    }
    public function addPatient(PatientRequest $request)
    {
        try {
            DB::beginTransaction();
            $user = $this->userRepository->addUser([
                'name' => $request->name,
                'email' => $request->email,
                'password' => bcrypt($request->password),
                'type' => 0
            ]);
            $photo = $this->uploadFile($request, 'photo', 'patientphoto');
            $patient = $this->patientRepository->addPatient([
                'Stage' => $request->Stage,
                'address' => $request->address,
                'birth_date' => $request->birth_date,
                'phone' => $request->phone,
                'photo' => $photo,
                'User_id' => $user->id,
                'gender' => $request->gender
            ]);
            $patient->caregivers()->syncWithoutDetaching($request->caregiver_id);
            DB::commit();
            $data = patientData($user);
            return responseJson(201, $data, 'Patient successfully Added');
        } catch (Exception $e) {
            DB::rollback();
            return responseJson(401, "", $e);
        }
    }

    public function getPatient($patient_id)
    {
        $patient = $this->patientRepository->getPatient($patient_id);
        if ($patient) {
            $data = patientData($patient->user);
            return responseJson(201, $data, 'data for patient');
        }
        return responseJson(401, '', 'this Pateint_id not found');
    }

    public function deletePatient($patient_id)
    {
        $patient = $this->patientRepository->getPatient($patient_id);
        if ($patient) {
            $this->deleteFile($patient->photo);
            $this->userRepository->deleteUser($patient->User_id);
            return responseJson(201, ' ', 'Patient deleted ');
        }
        return responseJson(401, '', 'this Pateint_id not found');
    }

    public function updatePatient(PatientRequest $request, $patient_id)
    {
        $patient = $this->patientRepository->getPatient($patient_id);
        if ($patient) {
            $this->userRepository->updateUser($patient->user->id, [
                'name' => $request->name,
                'email' => $request->email,
                'password' => bcrypt($request->password),
                'updated_at' => Carbon::now()
            ]);
            $photo = $this->uploadFile($request, 'photo', 'patientphoto');
            if ($photo!="Null") {
                $this->deleteFile($patient->photo);
            } else {
                $photo = $patient->photo;
            }
            $this->patientRepository->updatePatient($patient_id, [
                'Stage' => $request->Stage,
                'address' => $request->address,
                'birth_date' => $request->birth_date,
                'phone' => $request->phone,
                'photo' => $photo,
                'gender' => $request->gender
            ]);
            $data = patientData($patient->user);
            return responseJson(201, $data, 'Patient Updated ');
        }
        return responseJson(401, '', 'this Pateint_id not found');
    }

    public  function getPatientImage($patient_id)
    {
        $patient = Patient::find($patient_id);
        if ($patient) {
            return $this->getFile($patient->photo);
        }
        return responseJson(401, '', 'this Pateint_id not found');
    }
}
