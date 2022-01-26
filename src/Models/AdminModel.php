<?php

namespace Raydragneel\HerauthLib\Models;

use Raydragneel\HerauthLib\Entities\AdminEntity;

class AdminModel extends BaseModel implements UserInterface
{
	protected $table                = 'herauth_admin';
	protected $primaryKey           = 'id';
	protected $useAutoIncrement     = true;
	protected $insertID             = 0;
	protected $returnType           = AdminEntity::class;
	protected $useSoftDeletes       = true;
	protected $protectFields        = true;
	protected $allowedFields        = ['username', 'nama', 'password', 'deleted_at'];

	// Dates
	protected $useTimestamps        = true;
	protected $dateFormat           = 'datetime';
	protected $createdField         = 'created_at';
	protected $updatedField         = 'updated_at';
	protected $deletedField         = 'deleted_at';

	// Validation
	protected $validationRules      = [];
	protected $validationMessages   = [];
	protected $skipValidation       = false;
	protected $cleanValidationRules = true;

	// Callbacks
	protected $allowCallbacks       = true;
	protected $beforeInsert         = [];
	protected $afterInsert          = [];
	protected $beforeUpdate         = [];
	protected $afterUpdate          = [];
	protected $beforeFind           = [];
	protected $afterFind            = [];
	protected $beforeDelete         = [];
	protected $afterDelete          = [];

	public function attempt(AdminEntity $entity)
	{
		$admin = $this->where(['username' => $entity->username])->first();
		if ($admin) {
			if (!password_verify($entity->password_view, $admin->password)) {
				$this->setMessage(lang("Auth.badAttempt"));
				return false;
			}
			$this->setMessage(lang("Auth.loginSuccess", [$admin->nama]));
			return $admin;
		} else {
			$this->setMessage(lang("Auth.badAttempt"));
			return false;
		}
	}

	public function cekUser($username)
	{
		return $this->where(['username' => $username])->first();
	}

}
