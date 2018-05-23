<?php

namespace App\Http\Controllers\Users;

use App\Models\Portal\User;
use Illuminate\Http\Request;
use App\Models\Portal\SteamAPI;
use App\Http\Controllers\Controller;
use App\Models\Permissions\Permission;
use App\Models\Permissions\PermissionUser;

class UserController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $users = User::all();
        $unregistered = User::unregistered();
        $nonMembers = $users->reject(function ($user) {
            return collect(SteamAPI::members())->contains($user->steam_id);
        });

        return view('user.admin.index', compact('users', 'unregistered', 'nonMembers'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $user = User::findOrFail($request->user_id);

        return $this->update($request, $user);
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show(User $user)
    {
        $permissions = Permission::orderBy('name')->get();

        return view('user.admin.show', compact('user', 'permissions'));
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, User $user)
    {
        // Delete all permissions
        PermissionUser::where('user_id', $user->id)->get()->map(function ($item) {
            $item->delete();
        });

        foreach ($request->all() as $key => $value) {
            $permission = Permission::where('name', $key)->first();

            if ($permission) {
                // If permission exists, add it to user
                PermissionUser::create([
                    'user_id' => $user->id,
                    'permission_id' => $permission->id
                ]);
            }
        }
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        //
    }
}
