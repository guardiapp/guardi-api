<?php

namespace App\Repositories;

use App\Models\ManagerResidence;
use App\Models\User;

class ManagerResidenceRepository {

    public function findAllManagersByResidence(Int $residenceId) {
        return ManagerResidence::where("residence_id", "=", $residenceId)
            ->with(["managers"])
            ->get();
    }

    public function findAllManagersWhereResidenceNotIn(Int $residenceId) {
        return User::whereDoesntHave(
            'residences',
            function ($q) use ($residenceId) {
                $q->where('residences.id', $residenceId);
            }
        )->where("type", "=", "Manager")->get();
    }

    public function findAllResidencesByManager(Int $managerId) {
        return ManagerResidence::where("manager_id", "=", $managerId)
            ->with(["residences"])
            ->get();
    }

    public function findAll($filters) {
        return ManagerResidence::get();
    }

    public function createBulk(array $payload) {
        /*$payload = array_map(function ($element) {
            $element['state'] = "ACTIVE";
            return $element;
        }, $payload);*/
        $this->deleteByResidenceId($payload[0]["residence_id"]);
        ManagerResidence::Insert($payload);
    }

    public function deleteByManagerId(Int $managerId) {
        ManagerResidence::where("manager_id", "=", $managerId)->delete();
        return true;
    }

    public function deleteByResidenceId(Int $residenceId) {
        ManagerResidence::where("residence_id", "=", $residenceId)->delete();
        return true;
    }
}
