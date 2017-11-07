<?php
namespace Corp\Repositories;
use Corp\Permission;
use Corp\Repositories\Repository;
use Corp\Repositories\RolesRepository;
use Gate;
class PermissionsRepository extends Repository {

    protected $roles_repa;
    public function __construct(Permission $permission, RolesRepository $roles_repa)
    {
        $this->model = $permission;
        $this->roles_repa = $roles_repa;
    }
    public function changePermissions ($request) {

        if(Gate::denies('change', $this->model)) {
            abort(403);
        }

        $data = $request->except('_token');

        $roles = $this->roles_repa->get();



        foreach($roles as $value) {
            if(isset($data[$value->id])) {
                $value->savePermissions($data[$value->id]);
            }

            else {
                $value->savePermissions([]);
            }
        }

        return ['status' => 'Права обновлены'];
    }


}

?>