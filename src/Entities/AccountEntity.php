<?php

namespace Raydragneel\HerauthLib\Entities;

use CodeIgniter\Entity\Entity;
use Raydragneel\HerauthLib\Models\GroupModel;
use Raydragneel\HerauthLib\Models\GroupPermissionModel;
use Raydragneel\HerauthLib\Models\PermissionModel;
use Raydragneel\HerauthLib\Models\UserGroupModel;

class AccountEntity extends Entity
{
	protected $group_model;
	protected $user_group_model;
	protected $permission_model;
	protected $group_permission_model;
	public function __construct(array $data = null)
	{
		parent::__construct($data);
		$this->group_model = model(GroupModel::class);
		$this->user_group_model = model(UserGroupModel::class);
		$this->permission_model = model(PermissionModel::class);
		$this->group_permission_model = model(GroupPermissionModel::class);
	}

	public function inGroup($groups)
	{
		$username = $this->username;
		if ($username === 0) {
			return false;
		}

		if (!is_array($groups)) {
			$groups = [$groups];
		}
		$userGroups = $this->user_group_model->getGroupsForUser($username);
		if (empty($userGroups)) {
			return false;
		}

		foreach ($groups as $group) {
			if (is_numeric($group)) {
				$ids = array_column($userGroups, 'group_id');
				if (in_array($group, $ids)) {
					return true;
				}
			} else if (is_string($group)) {
				$names = array_column($userGroups, 'nama');
				if (in_array($group, $names)) {
					return true;
				}
			}
		}

		return false;
	}

	public function hasPermission($permission)
	{
		// @phpstan-ignore-next-line
		if (empty($permission) || (!is_string($permission) && !is_numeric($permission))) {
			return null;
		}
		$username = $this->username;
		if (empty($username)) {
			return null;
		}

		// Get the Permission ID
		$permissionId = $this->getPermissionID($permission);
		if (!is_numeric($permissionId)) {
			return false;
		}

		// First check the permission model. If that exists, then we're golden.
		if ($this->group_permission_model->doesUserHavePermission($username, (int)$permissionId)) {
			return true;
		}

		// Still here? Then we have one last check to make - any user private permissions.
		return $this->doesUserHavePermission($username, (int)$permissionId);
	}

	public function doesUserHavePermission($username, $permission)
	{
		$permissionId = $this->getPermissionID($permission);

		if (!is_numeric($permissionId)) {
			return false;
		}

		if (empty($username)) {
			return null;
		}

		return $this->group_permission_model->doesUserHavePermission($username, $permissionId);
	}

	protected function getPermissionID($permission)
	{
		// If it's a number, we're done here.
		if (is_numeric($permission)) {
			return (int) $permission;
		}

		// Otherwise, pull it from the database.
		$p = $this->permission_model->asObject()->where('nama', $permission)->first();

		if (!$p) {
			$this->error = lang('Api.user.permissionNotFound', [$permission]);

			return false;
		}

		return (int) $p->id;
	}

	public function groups()
	{
		return $this->user_group_model->join("herauth_group group", "herauth_user_group.group_id = group.id")->where('username', $this->username)->findColumn('nama');
	}

	public function addGroup($name)
	{
		$group = $this->group_model->findGroupByName($name);
		if($group){
			return $this->user_group_model->save([
				'username' => $this->username,
				'group_id' => $group->id
			]);
		}
		return false;
	}
	public function deleteGroup($name)
	{
		$user_group = $this->user_group_model->join("herauth_group group", "herauth_user_group.group_id = group.id")->where(['username' => $this->username,'nama' => $name])->first();
		if($user_group){
			return $this->user_group_model->delete($user_group->id);
		}
		return false;
	}

}
