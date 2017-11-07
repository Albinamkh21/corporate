<?php
namespace Corp\Repositories;
use Corp\Role;
use Corp\Repositories\Repository;

class RolesRepository extends Repository {

    public function __construct(Role $role)
    {
        $this->model = $role;
    }

}

?>