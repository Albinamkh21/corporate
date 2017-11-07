<?php

namespace Corp\Http\Controllers\Admin;

use Illuminate\Http\Request;
use Corp\Http\Controllers\Controller;
use Corp\Repositories\PermissionsRepository;
use Corp\Repositories\RolesRepository;
use Gate;

class PermissionController extends AdminController
{

    protected $permissions_repa;
    protected $roles_repa;
    public function __construct(PermissionsRepository $permissions_repa, RolesRepository $roles_repa)
    {
        parent ::__construct();
        $this->permissions_repa = $permissions_repa;
        $this->roles_repa = $roles_repa;
        $this->template = env('THEME').'.admin.permissions';
    }
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $this->checkUser();
        if(Gate::denies('EDIT_USERS', $this->user)){
            abort(403, "У Вас недостаточно прав!");
        }
        $this->title = 'Управление правами пользователей.';
        $roles = $this->getRoles();
        $permissions = $this->getPermissions();
        $content = view(env('THEME').'.admin.permissions_content')->with(['roles' => $roles, 'permissions' => $permissions])->render();
        $this->data = array_add($this->data, 'content',$content);

        return $this->renderOutput();

    }
    public function getRoles(){

        return $this->roles_repa->get();
    }
    public function getPermissions(){

        return $this->permissions_repa->get();
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
        $result = $this->permissions_repa->changePermissions($request);

        if(is_array($result) && !empty($result['error'])) {
            return back()->with($result);
        }

        return back()->with($result);
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        //
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
    public function update(Request $request, $id)
    {
        //
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
