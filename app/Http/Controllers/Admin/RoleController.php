<?php

namespace App\Http\Controllers\Admin;

use App\Role;
use Illuminate\Http\Request;
use App\Services\UserService;
use Illuminate\Support\Facades\DB;
use App\Http\Resources\RoleResource;
use Symfony\Component\HttpFoundation\Response;

class RoleController
{

    public function index()
    {
        (new UserService())->allows('view', 'roles');

        return RoleResource::collection(Role::all());
    }

    public function store(Request $request)
    {
        (new UserService())->allows('view', 'roles');

        $role = Role::create($request->only('name'));

        if ($permissions = $request->input('permissions')) {
            foreach ($permissions as $permission_id) {
                DB::table('role_permission')->insert([
                    'role_id' => $role->id,
                    'permission_id' => $permission_id,
                ]);
            }
        }

        return response(new RoleResource($role), Response::HTTP_CREATED);
    }


    public function show($id)
    {
        (new UserService())->allows('view', 'roles');

        return new RoleResource(Role::findOrFail($id));
    }


    public function update(Request $request, $id)
    {
        (new UserService())->allows('view', 'roles');


        $role = Role::findOrFail($id);

        $role->update($request->only('name'));

        DB::table('role_permission')->where(['role_id' => $role->id])->delete();

        if ($permissions = $request->input('permissions')) {
            foreach ($permissions as $permission_id) {
                DB::table('role_permission')->insert([
                    'role_id' => $role->id,
                    'permission_id' => $permission_id,
                ]);
            }
        }


        return response(new RoleResource($role), Response::HTTP_ACCEPTED);
    }


    public function destroy($id)
    {
        (new UserService())->allows('view', 'roles');


        DB::table('role_permission')->where(['role_id' => $id])->delete();

        Role::destroy($id);

        return response(null, Response::HTTP_NO_CONTENT);
    }
}
