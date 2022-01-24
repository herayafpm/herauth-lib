<?php

namespace Raydragneel\HerauthLib\Controllers\Master;

use CodeIgniter\Exceptions\PageNotFoundException;
use Raydragneel\HerauthLib\Models\ClientModel;

class Client extends BaseController
{
    protected $modelName = ClientModel::class;

    public function index()
    {
        $data = [
            'page_title' => lang("Web.master.client"),
            'url_datatable' => herauth_web_url($this->root_view . "client/datatable"),
            'url_add' => herauth_base_locale_url($this->root_view . "client/add"),
            'url_edit' => herauth_base_locale_url($this->root_view . "client/edit/"),
            'url_delete' => herauth_web_url($this->root_view . "client/delete/"),
            'url_restore' => herauth_web_url($this->root_view . "client/restore/"),
            'url_regenerate_key' => herauth_web_url($this->root_view . "client/regenerate_key/"),
            'url_permissions' => herauth_base_locale_url($this->root_view . "client/permissions/"),
            'url_whitelists' => herauth_base_locale_url($this->root_view . "client/whitelists/"),
        ];
        return $this->view("client/index", $data);
    }

    public function list()
    {
        return view($this->root_view . "client/table", [
            '_json' => $this->getDataRequest(),
            'url_delete' => herauth_web_url($this->root_view . "client/delete/"),
            'url_restore' => herauth_web_url($this->root_view . "client/restore/")
        ]);
    }

    public function add()
    {
        $data = [
            'page_title' => lang("Web.add")." ".lang("Web.master.client"),
            'url_add' => herauth_web_url($this->root_view . "client/add"),
        ];
        return $this->view("client/add", $data);
    }
    public function edit($id = null)
    {
        $client = $this->model->withDeleted(true)->find($id);
        if (!$client) {
            throw new PageNotFoundException();
        }

        $data = [
            'page_title' => lang("Web.edit")." ".lang("Web.master.client")." " . $client->nama,
            'client' => $client,
            'url_edit' => herauth_web_url($this->root_view . "client/edit/".$id),
        ];
        return $this->view("client/edit", $data);
    }

    public function permissions($id = null)
    {
        $client = $this->model->withDeleted(true)->find($id);
        if (!$client) {
            throw new PageNotFoundException();
        }

        $data = [
            'page_title' => lang("Web.master.client")." ".lang("Web.master.permission")." " . $client->nama,
            'client' => $client,
            'url_save' => herauth_web_url($this->root_view . "client/save_permissions/".$id),
            'url_permissions' => herauth_web_url($this->root_view . "permission"),
            'url_client_permissions' => herauth_web_url($this->root_view . "client/permissions/".$id),
        ];
        return $this->view("client/permission", $data);
    }
    public function whitelists($id = null)
    {
        $client = $this->model->withDeleted(true)->find($id);
        if (!$client) {
            throw new PageNotFoundException();
        }

        $data = [
            'page_title' => lang("Web.master.client")." ".lang("Web.master.whitelist")." " . $client->nama,
            'client' => $client,
            'url_save' => herauth_web_url($this->root_view . "client/save_whitelists/".$id),
        ];
        return $this->view("client/whitelist", $data);
    }

}
