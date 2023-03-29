<?php

namespace App\Http\Controllers;

use Spatie\Permission\Models\Role;
use Spatie\Permission\Models\Permission;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Gate;
use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\StoreRolesRequest;
use App\Http\Requests\Admin\UpdateRolesRequest;
use Session;

class RolesController extends Controller
{
	/**
	 * Display a listing of Role.
	 *
	 * @return \Illuminate\Http\Response
	 */
	
	public function index()
	{
		if (! Gate::allows('roles_manage')) {
			return abort(401);
		}
		else{
			$roles = Role::all();

			return view('admin.roles.index', compact('roles'));
		}

	}

	/**
	 * Show the form for creating new Role.
	 *
	 * @return \Illuminate\Http\Response
	 */
	public function create()
	{
		if (! Gate::allows('role_create')) {
			return abort(401);
		}
		else{
			$permissions = Permission::get()->pluck('name', 'name');

			return view('admin.roles.create', compact('permissions'));
		}
	}

	/**
	 * Store a newly created Role in storage.
	 *
	 * @param  \App\Http\Requests\StoreRolesRequest  $request
	 * @return \Illuminate\Http\Response
	 */
	public function store(StoreRolesRequest $request)
	{
		if (! Gate::allows('role_create')) {
			return abort(401);
		}
		else{
			$role = Role::create($request->except('permission'));
			$permissions = $request->input('permission') ? $request->input('permission') : [];
			$role->givePermissionTo($permissions);

			return redirect()->route('roles.index')->with('success', 'New role has been created successfully!');
		}
	}

	/**
	 * Show the form for editing Role.
	 *
	 * @param  int  $id
	 * @return \Illuminate\Http\Response
	 */
	public function show($id)
	{
		if (! Gate::allows('roles_manage')) {
			return abort(401);
		}
		else{
			$permissions = Permission::get()->pluck('name', 'name');

			$role = Role::findOrFail($id);

			return view('admin.roles.edit', compact('role', 'permissions'));
		}
	}

	/**
	 * Show the form for editing Role.
	 *
	 * @param  int  $id
	 * @return \Illuminate\Http\Response
	 */
	public function edit($id)
	{
		if (! Gate::allows('role_edit')) {
			return abort(401);
		}
		else{
			$permissions = Permission::get()->pluck('name', 'name');

			$role = Role::findOrFail($id);

			return view('admin.roles.edit', compact('role', 'permissions'));
		}
	}

	/**
	 * Update Role in storage.
	 *
	 * @param  \App\Http\Requests\UpdateRolesRequest  $request
	 * @param  int  $id
	 * @return \Illuminate\Http\Response
	 */
	public function update(UpdateRolesRequest $request, $id)
	{
		if (! Gate::allows('role_edit')) {
			return abort(401);
		}
		else{
			$role = Role::findOrFail($id);
			$role->update($request->except('permission'));
			$permissions = $request->input('permission') ? $request->input('permission') : [];
			$role->syncPermissions($permissions);

			return redirect()->route('roles.index')->with('success', 'Role has been updated successfully!');
		}
	}


	/**
	 * Remove Role from storage.
	 *
	 * @param  int  $id
	 * @return \Illuminate\Http\Response
	 */
	public function destroy(Request $req)
	{
		if (! Gate::allows('role_delete')) {
			return abort(401);
		}
		else{
			$role = Role::findOrFail($req->role_id);
			$role->delete();
			return json_encode(['status' => 'success', 'message' => 'Role has been deleted successfully!']);
		}
	}

	/**
	 * Delete all selected Role at once.
	 *
	 * @param Request $request
	 */
	
	public function massDestroy(Request $request)
	{
		if (! Gate::allows('role_delete')) {
			return abort(401);
		}
		else{
			if ($request->input('ids')) {
				$entries = Role::whereIn('id', $request->input('ids'))->get();

				foreach ($entries as $entry) {
					$entry->delete();
				}
			}
		}
	}

	public function checkHasPermission(Request $req)
	{      
		$role = Role::where('name', $req->role)->value('id');
		$role = Role::findOrFail($role);
		if($role->hasPermissionTo($req->permission)){
			return json_encode(['status'=> 'success', 'response'=> true]);
		}
		else{
			return json_encode(['status'=> 'failed', 'response'=> false]);
		}
		// echo $req->permission;
	}
}
