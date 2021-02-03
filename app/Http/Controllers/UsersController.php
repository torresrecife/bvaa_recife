<?php

namespace App\Http\Controllers;

use App\Grids\UsersGrid;
use App\Grids\UsersGridInterface;
use App\Role;
use App\User;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class UsersController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @param UsersGridInterface $usersGrid
     * @param Request $request
     * @return \Illuminate\Http\Response
     */
    public function index(UsersGridInterface $usersGrid, Request $request)
    {
        return $usersGrid->create(['query' => User::query(), 'request' => $request])
            ->renderOn('render_grid');
    }

    public function index2(Request $request)
    {
        $banks = [
            'RCJ VAREJO - BANCO J. SAFRA S/A',
            'BUSCA - BANCO J. SAFRA S/A',
            'BUSCA - BANCO SAFRA S/A',
            'BUSCA - SAFRA LEASING S/A ARRENDAMENTO MERCANTIL',
            'RCJ VAREJO - BANCO SAFRA S/A',
            'RCJ VAREJO - SAFRA LEASING S/A ARRENDAMENTO MERCANTIL'
        ];

        $query = DB::connection('sqlsrv')
            ->table('Processo')
            ->where('StatusProcesso','Ativo')
            ->whereIn('Carteira',$banks);

        //$query = DB::table('users');
        return (new UsersGrid()) // you can then use it as $this->user within the class. It's set implicitly using the __set() call
        ->create(['query' => $query, 'request' => $request])
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
            'model' => class_basename(User::class),
            'route' => route('users.store'),
            'action' => 'create',
            'pjaxContainer' => $request->get('ref'),
        ];

        // modal
        return view('users_modal', compact('modal'))->render();
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request $request
     * @return JsonResponse|\Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $this->validate($request, [
            'name' => 'required|min:3',
            'email' => 'required|email|unique:users'
        ]);

        User::creating(function ($user) {
            $user->role_id = Role::query()->select('id')->get()->random()->id;
            $user->password = bcrypt(str_random());
        });

        $user = User::query()->create($request->all());

        return new JsonResponse([
            'success' => true,
            'message' => 'User with id ' . $user->id . ' has been created.'
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
        $user = User::query()->findOrFail($id);

        $modal = [
            'model' => class_basename(User::class),
            'route' => route('users.update', ['user' => $user->id]),
            'pjaxContainer' => $request->get('ref'),
            'method' => 'patch',
            'action' => 'update'
        ];

        // modal
        return view('users_modal', compact('modal', 'user'))->render();
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request $request
     * @param  int $id
     * @return JsonResponse|\Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        $this->validate($request, [
            'name' => 'required|min:3|max:30',
            'email' => 'required|email|unique:users,id,' . $id,
        ]);

        $status = User::query()->findOrFail($id)->update($request->all());

        if ($status) {

            return new JsonResponse([
                'success' => true,
                'message' => 'user with id ' . $id . ' has been updated.'
            ]);
        }
        return new JsonResponse(['success' => false], 400);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int $id
     * @return JsonResponse|\Illuminate\Http\Response
     * @throws \Exception
     */
    public function destroy($id)
    {
        $status = User::query()->findOrFail($id)->delete();

        return new JsonResponse([
            'success' => $status,
            'message' => 'user with id ' . $id . ' has been deleted.'
        ]);
    }
}
