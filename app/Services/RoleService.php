<?php

namespace App\Services;

use App\Models\User;
use Exception;

class RoleService
{

    public function findbyId($userId)
    {
        $user = User::findOrFail($userId);
        return $user;
    }
    
    public function assignRole($userId, $roleName)
    {
        try {
            $user = $this->findbyId($userId);
            return $user->assignRole($roleName);
        } catch (Exception $e) {
            throw new Exception(" Role assignment error: " . $e->getMessage());
        }
    }

    public function revokeRole($userId, $roleName)
    {
        try {
             $user = $this->findbyId($userId);
            return $user->removeRole($roleName);
        } catch (Exception $e) {
            throw new Exception("Role revoke error :" . $e->getMessage());
        }
    }

    public function updateRole($userId, $newRole)
    {
        try {
             $user = $this->findbyId($userId);
            return $user->syncRoles([$newRole]);
        } catch (Exception $e) {
            throw new Exception("Role revoke error :" . $e->getMessage());
        }
    }
}
