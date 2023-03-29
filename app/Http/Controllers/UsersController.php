<?php

namespace App\Http\Controllers;

use App\Models\User;
use Spatie\Permission\Models\Role;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Gate;
use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\StoreUsersRequest;
use App\Http\Requests\Admin\UpdateUsersRequest;

class UsersController extends Controller
{
	/**
	 * Display a listing of User.
	 *
	 * @return \Illuminate\Http\Response
	 */

	public function index()
	{
		if (Gate::allows('roles_manage') || Gate::allows('users_manage')) {
			$users = User::all();

			return view('admin.users.index', compact('users'));
		}
		else{
			return abort(401);
		}
	}

	/**
	 * Show the form for creating new User.
	 *
	 * @return \Illuminate\Http\Response
	 */

	public function create()
	{
		if (! Gate::allows('users_create')) {
			return abort(401);
		}
		else{
			$roles = Role::get()->pluck('name', 'name');

			return view('admin.users.create', compact('roles'));
		}
	}

	/**
	 * Store a newly created User in storage.
	 *
	 * @param  \App\Http\Requests\StoreUsersRequest  $request
	 * @return \Illuminate\Http\Response
	 */

	public function store(StoreUsersRequest $request)
	{
		if (! Gate::allows('users_create')) 
		{
			return abort(401);
		}
		else{
			$user = User::create($request->all());
			$roles = $request->input('roles') ? $request->input('roles') : [];
			$user->assignRole($roles);

			return redirect()->route('users.index')->with('success', 'New user has been created successfully!');
		}
	}

	/**
	 * Show the form for editing User.
	 *
	 * @param  int  $id
	 * @return \Illuminate\Http\Response
	 */
	
	public function show($id)
	{
		if (! Gate::allows('users_view')) {
			return abort(401);
		}
		else{
			$roles = Role::get()->pluck('name', 'name');

			$user = User::findOrFail($id);

			return view('admin.users.edit', compact('user', 'roles'));
		}
	}

	/**
	 * Show the form for editing User.
	 *
	 * @param  int  $id
	 * @return \Illuminate\Http\Response
	 */
	
	public function edit($id)
	{
		if (! Gate::allows('users_edit')) {
			return abort(401);
		}
		else{
			$roles = Role::get()->pluck('name', 'name');

			$user = User::findOrFail($id);

			return view('admin.users.edit', compact('user', 'roles'));
		}
	}


	/**
	 * Update User in storage.
	 *
	 * @param  \App\Http\Requests\UpdateUsersRequest  $request
	 * @param  int  $id
	 * @return \Illuminate\Http\Response
	 */

	public function update(UpdateUsersRequest $request, $id)
	{
		if (! Gate::allows('users_edit')) {
			return abort(401);
		}
		else{
			$data = [
				'name' => $request->name,
				'email' => $request->email,
				'roles' => $request->roles,
			];
			if (isset($request->password)) {
				$data['password'] = $request->password;
			}
			$user = User::findOrFail($id);
			$user->update($request->all());
			$roles = $request->input('roles') ? $request->input('roles') : [];
			$user->syncRoles($roles);

			return redirect()->route('users.index')->with('success', 'User has been updated successfully!');
		}
	}

	/**
	 * Remove User from storage.
	 *
	 * @param  int  $id
	 * @return \Illuminate\Http\Response
	 */
	
	public function destroy(Request $req)
	{
		if (! Gate::allows('users_delete')) {
			return abort(401);
		}
		else{
			$id = $req->user_id;
			$user = User::findOrFail($id);
			$user->delete();
			return json_encode(['status' => 'success', 'message' => 'User has been deleted successfully!']);

		}
	}

	/**
	 * Delete all selected User at once.
	 *
	 * @param Request $request
	 */

	public function massDestroy(Request $request)
	{
		// dd("here");
		if (! Gate::allows('users_delete')) {
			return abort(401);
		}
		else{
			if ($request->input('selectedId')) {
				$entries = User::whereIn('id', $request->input('selectedId'))->get();

				foreach ($entries as $entry) {
					$entry->delete();
				}
			}
		}
	}
}

