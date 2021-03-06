<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreRoleRequest;
use App\Role;
use App\Route;

class RolesController extends Controller
{
    /**
     * List all roles.
     *
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function index()
    {
        $roles = Role::with(['users', 'routes'])
            ->get()
            ->sortBy('name', SORT_ASC);

        return view('roles.index', compact('roles'));
    }

    /**
     * Render create new role form.
     *
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function create()
    {
        $routes = Route::all()->sortBy('uri', SORT_ASC);

        return view('roles.create', compact('routes'));
    }

    /**
     * Create new role with the
     *
     * @param StoreRoleRequest $request
     *
     * @return \Illuminate\Http\RedirectResponse
     */
    public function store(StoreRoleRequest $request)
    {
        $role = Role::create($request->all());
        $role->assignRoutes($request->get('route_id'));

        return redirect()
            ->route('roles.index')
            ->with('status', 'Role created!');
    }

    /**
     * Render edit role form.
     *
     * @param Role $role
     *
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function edit(Role $role)
    {
        $routes = Route::all()->sortBy('uri', SORT_ASC);

        return view('roles.edit', compact('role', 'routes'));
    }

    /**
     * Update role details.
     *
     * @param StoreRoleRequest $request
     * @param Role $role
     *
     * @return \Illuminate\Http\RedirectResponse
     */
    public function update(StoreRoleRequest $request, Role $role)
    {
        $role->update($request->all());
        $role->routes()->sync($request->get('route_id'));

        return redirect()
            ->route('roles.index')
            ->with('status', 'Profile updated!');
    }

    /**
     * Remove role.
     *
     * @param int $id
     *
     * @return \Illuminate\Contracts\Routing\ResponseFactory|\Symfony\Component\HttpFoundation\Response
     */
    public function destroy($id)
    {
        Role::destroy($id);

        return response('', 204);
    }
}
