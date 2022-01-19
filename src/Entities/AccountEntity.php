<?php namespace Raydragneel\HerauthLib\Entities;

use CodeIgniter\Entity\Entity;
use Raydragneel\HerauthLib\Models\GroupModel;
use Raydragneel\HerauthLib\Models\UserGroupModel;

class AccountEntity extends Entity{
    protected $group_model;
	protected $user_group_model;
	public function __construct(array $data = null)
	{
		parent::__construct($data);
		$this->group_model = model(GroupModel::class);
		$this->user_group_model = model(UserGroupModel::class);
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

	public function groups()
	{
		return $this->user_group_model->join("herauth_group group","herauth_user_group.group_id = group.id")->where('username',$this->username)->findColumn('nama');
	}
}