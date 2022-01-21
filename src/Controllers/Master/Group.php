<?php

namespace Raydragneel\HerauthLib\Controllers\Master;

use Raydragneel\HerauthLib\Models\GroupModel;
use CodeIgniter\Exceptions\PageNotFoundException;

class Group extends BaseController
{
    protected $modelName = GroupModel::class;

    public function index()
    {
        $data = [
            'page_title' => 'Group',
            'url_datatable' => herauth_web_url($this->root_view . "group/datatable"),
            'url_add' => herauth_base_locale_url($this->root_view . "group/add"),
            'url_edit' => herauth_base_locale_url($this->root_view . "group/edit/"),
            'url_delete' => herauth_web_url($this->root_view . "group/delete/"),
            'url_restore' => herauth_web_url($this->root_view . "group/restore/"),
        ];
        return $this->view("group/index", $data);
    }

    public function list()
    {
        return view($this->root_view . "group/table", [
            '_json' => $this->getDataRequest(),
            'url_delete' => herauth_web_url($this->root_view . "group/delete/"),
            'url_restore' => herauth_web_url($this->root_view . "group/restore/"),
            'datas' => $this->model->orderBy('nama', 'asc')->withDeleted(true)->findAll()
        ]);
    }

    public function add()
    {
        $data = [
            'page_title' => 'Tambah Group',
            'url_add' => herauth_web_url($this->root_view . "group/add"),
        ];
        return $this->view("group/add", $data);
    }
    public function edit($id = null)
    {
        $group = $this->model->find($id);
        if (!$group) {
            throw new PageNotFoundException();
        }

        $data = [
            'page_title' => 'Edit Group ' . $group['nama'],
            'url_add' => herauth_web_url($this->root_view . "group/add"),
        ];
        return $this->view("group/add", $data);
    }

}
