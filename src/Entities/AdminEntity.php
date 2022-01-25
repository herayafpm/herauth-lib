<?php

namespace Raydragneel\HerauthLib\Entities;

use Raydragneel\HerauthLib\Models\UserGroupModel;

class AdminEntity extends AccountEntity
{
	public function __construct(array $data = null)
	{
		parent::__construct($data);
	}
	public $password_view = "";
	protected $datamap = [];
	protected $dates   = [
		'created_at',
		'updated_at',
		'deleted_at',
	];
	protected $casts   = [];

	public function setPassword($pass)
	{
		$this->attributes['password'] = password_hash($pass, PASSWORD_DEFAULT);
		$this->password_view = $pass;
		return $this;
	}

	public function getGroups($limit = -1, $offset = 0)
	{
		$user_group_model = model(UserGroupModel::class);
		if($limit > 0){
			return $user_group_model->select('id,group_id')->where(['username' => $this->attributes['username']])->findAll($limit,$offset);
		}else{
			return $user_group_model->select('id,group_id')->where(['username' => $this->attributes['username']])->findAll();
		}
	}

}
