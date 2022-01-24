<?php

namespace Raydragneel\HerauthLib\Entities;

use CodeIgniter\Entity\Entity;
use Raydragneel\HerauthLib\Models\GroupPermissionModel;

class GroupEntity extends Entity
{
    public function __construct(array $data = null)
    {
        parent::__construct($data);
    }
    protected $datamap = [];
    protected $dates   = [
        'created_at',
        'updated_at',
        'deleted_at',
    ];
    protected $casts   = [];

    public function getPermissions()
    {
        $group_permission_model = model(GroupPermissionModel::class);
        return $group_permission_model->where(['group_id' => $this->attributes['id']])->findAll();
    }

}
