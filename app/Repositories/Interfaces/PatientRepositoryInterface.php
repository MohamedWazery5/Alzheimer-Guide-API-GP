<?php
namespace App\Repositories\Interfaces;
    interface PatientRepositoryInterface{
        public function addPatient(array $data);
        public function getPatient($patientID);
        // public function deletePatient($patientID);
        public function updatePatient($patientID,array $data);
    }
?>