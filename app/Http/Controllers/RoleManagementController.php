<?php

namespace App\Http\Controllers;

use App\Http\Requests\RoleRequest;
use Exception;
use App\Services\RoleService;
use Illuminate\Database\Eloquent\ModelNotFoundException;

class RoleManagementController extends Controller
{
    protected $roleService;

    public function __construct(RoleService $roleService)
    {
        $this->roleService = $roleService;
    }

    public function assign(RoleRequest $request)
    {
        try {
            $this->roleService->assignRole($request->user_id, $request->role);

            return response_success(null, 200, 'Role assigned successfully.');
        } catch (ModelNotFoundException $e) {
            return response_error(null, 404, 'User not found');
        } catch (Exception $e) {
            return response_error(null, 400, $e->getMessage());
        }
    }

    public function revoke(RoleRequest $request)
    {
        try {
            $this->roleService->revokeRole($request->user_id, $request->role);

            return response_success(null, 200, 'Role revoked successfully.');
        } catch (ModelNotFoundException $e) {
            return response_error(null, 404, 'User not found');
        } catch (Exception $e) {
            return response_error(null, 400, $e->getMessage());
        }
    }

    public function update(RoleRequest $request)
    {
        try {

            $this->roleService->updateRole($request->user_id, $request->role);
            return response_success(null, 200, 'User role updated successfully.');
        } catch (ModelNotFoundException $e) {
            return response_error(null, 404, 'User not found');
        } catch (Exception $e) {
            return response_error(null, 400, $e->getMessage());
        }
    }

    // Test
    public function checkMyRole()
    {
        $user = auth()->user();

        return response_success([
            'name' => $user->name,
            'email' => $user->email,
            'roles' => $user->getRoleNames(), 
        ], 200, 'User data and roles');
    }
}
