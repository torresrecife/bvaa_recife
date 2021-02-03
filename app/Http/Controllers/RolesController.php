<?php

namespace App\Http\Controllers;

use App\Grids\RolesGridInterface;
use App\Role;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class RolesController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @param RolesGridInterface $rolesGrid
     * @param Request $request
     * @return \Illuminate\Http\Response
     */
    public function index(RolesGridInterface $rolesGrid, Request $request)
    {
        $query = Role::query();

        return $rolesGrid->create(['request' => $request, 'query' => $query])->renderOn('render_grid');
    }

    public function index2(Request $request)
    {
        $user = $request->user();
        return (new UsersGrid(['user' => $user])) // you can then use it as $this->user within the class. It's set implicitly using the __set() call
        ->create(['query' => User::query(), 'request' => $request])
            ->renderOn('render_grid');
    }

    /**
     * Show the form for creating a new resource.
     *
     * @param Request $request
     * @return \Illuminate\Http\Response
     * @throws \Throwable
     */
    public function create(Request $request)
    {
        $modal = [
            'model' => class_basename(Role::class),
            'route' => route('roles.store'),
            'action' => 'create',
            'pjaxContainer' => $request->get('ref'),
        ];

        // modal
        return view('roles_modal', compact('modal'))->render();
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return JsonResponse
     */
    public function store(Request $request)
    {
        $this->validate($request, [
            'name' => 'required|unique:roles|min:3|max:30',
            'description' => 'required|max:500'
        ]);

        $user = Role::query()->create($request->all());

        return new JsonResponse([
            'success' => true,
            'message' => 'Role with id ' . $user->id . ' has been created.'
        ]);
    }

    /**
     * Display the specified resource.
     *
     * @param  int $id
     * @param Request $request
     * @return \Illuminate\Http\Response
     * @throws \Throwable
     */
    public function show($id, Request $request)
    {
        $role = Role::with('users')->findOrFail($id);

        $modal = [
            'model' => class_basename(Role::class),
            'route' => route('roles.update', ['user' => $role->id]),
            'pjaxContainer' => $request->get('ref'),
            'method' => 'patch',
            'action' => 'update'
        ];

        // modal
        return view('roles_modal', compact('modal', 'role'))->render();
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
     * @return JsonResponse
     */
    public function update(Request $request, $id)
    {
        $this->validate($request, [
            'name' => 'required|min:3|max:30|unique:roles,id,' . $id,
            'description' => 'required|max:500'
        ]);

        $status = Role::query()->findOrFail($id)->update($request->all());

        if ($status) {

            return new JsonResponse([
                'success' => true,
                'message' => 'role with id ' . $id . ' has been updated.'
            ]);
        }
        return new JsonResponse(['success' => false], 400);
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
