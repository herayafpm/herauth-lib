<?php

namespace Raydragneel\HerauthLib\Controllers\Master;

use Raydragneel\HerauthLib\Models\GroupModel;

class Group extends BaseController
{
    public function index()
    {
        $data = [
            'page_title' => 'Group',
            'url_list' => base_url($this->request->uri->getPath() . "/list"),
            'url_add' => base_url($this->request->uri->getPath() . "/add"),
        ];
        return $this->view("group/index", $data);
    }

    public function list()
    {
        return view($this->root_view . "group/table", [
            '_json' => $this->getDataRequest(),
            'url_delete' => herauth_web_url($this->root_view . "group/delete/"),
            'url_edit' => herauth_web_url($this->root_view . "group/edit/"),
            'datas' => model(GroupModel::class)->orderBy('nama', 'asc')->withDeleted(true)->findAll()
        ]);
    }
}
