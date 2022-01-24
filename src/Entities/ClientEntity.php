<?php

namespace Raydragneel\HerauthLib\Entities;

use Raydragneel\HerauthLib\Models\ClientPermissionModel;
use CodeIgniter\Entity\Entity;
use DomainException;
use Raydragneel\HerauthLib\Models\ClientWhitelistModel;
use Raydragneel\HerauthLib\Models\PermissionModel;

class ClientEntity extends Entity
{
    protected $permission_model;
    protected $client_permission_model;
    protected $client_whitelist_model;
    public function __construct(array $data = null)
    {
        parent::__construct($data);
        $this->permission_model = model(PermissionModel::class);
        $this->client_permission_model = model(ClientPermissionModel::class);
        $this->client_whitelist_model = model(ClientWhitelistModel::class);
    }
    protected $datamap = [];
    protected $dates   = [
        'expired',
        'created_at',
        'updated_at',
        'deleted_at',
    ];
    protected $casts   = [];


    public function hasPermission($permission)
    {

        // @phpstan-ignore-next-line
        if (empty($permission) || (!is_string($permission) && !is_numeric($permission))) {
            return null;
        }
        $client_id = $this->attributes['id'];

        if (empty($client_id)) {
            return null;
        }

        // Get the Permission ID
        $permissionId = $this->getPermissionID($permission);

        if (!is_numeric($permissionId)) {
            return false;
        }
        // First check the permission model. If that exists, then we're golden.
        if ($this->client_permission_model->doesClientHavePermission($client_id, (int)$permissionId)) {
            return true;
        }

        // Still here? Then we have one last check to make - any user private permissions.
        return $this->doesClientHavePermission($client_id, (int)$permissionId);
    }

    public function doesClientHavePermission($client_id, $permission)
    {
        $permissionId = $this->getPermissionID($permission);

        if (!is_numeric($permissionId)) {
            return false;
        }

        if (empty($client_id)) {
            return null;
        }

        return $this->client_permission_model->doesClientHavePermission($client_id, $permissionId);
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
            $this->error = lang('Client.permission.notFound', [$permission]);

            return false;
        }

        return (int) $p->id;
    }


    public function cekWhitelist()
    {
        $request = service('request');
        $agent = $request->getUserAgent();
        $where = ['client_id' => $this->attributes['id'],'whitelist_type' => null,'whitelist_key' => null];
        if($agent->isMobile()){
            if ($agent->isMobile('android')) {
                if(!$request->hasHeader('android-key')){
                    throw new DomainException(lang("Filters.androidKey.IsRequired"));
                }
                $androidKey = $request->getHeader('android-key')->getValue() ?? '';
                if (empty($androidKey)) {
                    throw new DomainException(lang("Filters.androidKey.cannotEmpty"));
                }
                $where['whitelist_type'] = 'android';
                $where['whitelist_key'] = $androidKey;
            } else if ($agent->isMobile('iphone')) {
                if(!$request->hasHeader('ios-key')){
                    throw new DomainException(lang("Filters.iosKey.IsRequired"));
                }
                $iosKey = $request->getHeader('ios-key')->getValue() ?? '';
                if (empty($iosKey)) {
                    throw new DomainException(lang("Filters.iosKey.cannotEmpty"));
                }
                $where['whitelist_type'] = 'ios';
                $where['whitelist_key'] = $iosKey;
            }
        }else{
            $where['whitelist_type'] = 'ip';
            $where['whitelist_key'] = $request->getIPAddress();
        }

        $whitelist = $this->client_whitelist_model->where($where)->first();
        if (empty($whitelist)) {
            throw new DomainException(lang("Client.whitelist.unauthorized"));
        }
    }

    public function getClientEncodeText()
    {
        $request = service('request');
        $agent = $request->getUserAgent();
        $where = ['client_id' => $this->attributes['id'],'whitelist_type' => null,'whitelist_key' => null];
        if($agent->isMobile()){
            if ($agent->isMobile('android')) {
                if($request->hasHeader('android-key')){
                    $androidKey = $request->getHeader('android-key')->getValue() ?? '';
                    if(!empty($androidKey)){
                        $where['whitelist_type'] = 'android';
                        $where['whitelist_key'] = $androidKey;
                    }
                }
            } else if ($agent->isMobile('iphone')) {
                if($request->hasHeader('ios-key')){
                    $iosKey = $request->getHeader('ios-key')->getValue() ?? '';
                    if(!empty($iosKey)){
                        $where['whitelist_type'] = 'ios';
                        $where['whitelist_key'] = $iosKey;
                    }
                }
            }
        }else{
            $where['whitelist_type'] = 'ip';
            $where['whitelist_key'] = $request->getIPAddress();
        }

        $whitelist = $this->client_whitelist_model->where($where)->first();
        if (!empty($whitelist)) {
            return $this->attributes['nama']." " .$whitelist->whitelist_name;
        }
        return null;
    }

    public function getPermissions()
    {
        $client_permission_model = model(ClientPermissionModel::class);
        return $client_permission_model->where(['client_id' => $this->attributes['id']])->findAll();
    }

    public function getClientWhitelistWeb()
    {
        return $this->clientWhitelist('ip');
    }
    public function getClientWhitelistAndroid()
    {
        return $this->clientWhitelist('android');
    }
    public function getClientWhitelistIOS()
    {
        return $this->clientWhitelist('ios');
    }

    protected function clientWhitelist($type)
    {
        $client_whitelist_model = model(ClientWhitelistModel::class);
        return $client_whitelist_model->select("id,whitelist_name,whitelist_key")->where(['whitelist_type' => $type])->findAll();
    }


}
