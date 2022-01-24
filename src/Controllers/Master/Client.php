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
        ];
        return $this->view("client/index", $data);
    }

    public function list()
    {
        return view($this->root_view . "client/table", [
            '_json' => $this->getDataRequest(),
            'url_delete' => herauth_web_url($this->root_view . "client/delete/"),
            'url_restore' => herauth_web_url($this->root_view . "client/restore/"),
            'datas' => $this->model->orderBy('nama', 'asc')->withDeleted(true)->findAll()
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

}
