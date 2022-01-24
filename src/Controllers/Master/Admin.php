<?php

namespace Raydragneel\HerauthLib\Controllers\Master;

use CodeIgniter\Exceptions\PageNotFoundException;
use Raydragneel\HerauthLib\Models\AdminModel;

class Admin extends BaseController
{
    protected $modelName = AdminModel::class;

    public function index()
    {
        $data = [
            'page_title' => lang("Web.master.admin"),
            'url_datatable' => herauth_web_url($this->root_view . "admin/datatable"),
            'url_add' => herauth_base_locale_url($this->root_view . "admin/add"),
            'url_edit' => herauth_base_locale_url($this->root_view . "admin/edit/"),
            'url_delete' => herauth_web_url($this->root_view . "admin/delete/"),
            'url_restore' => herauth_web_url($this->root_view . "admin/restore/"),
        ];
        return $this->view("admin/index", $data);
    }

    public function list()
    {
        return view($this->root_view . "admin/table", [
            '_json' => $this->getDataRequest(),
            'url_delete' => herauth_web_url($this->root_view . "admin/delete/"),
            'url_restore' => herauth_web_url($this->root_view . "admin/restore/")
        ]);
    }

    public function add()
    {
        $data = [
            'page_title' => lang("Web.add")." ".lang("Web.master.admin"),
            'url_add' => herauth_web_url($this->root_view . "admin/add"),
        ];
        return $this->view("admin/add", $data);
    }
    public function edit($id = null)
    {
        $admin = $this->model->withDeleted(true)->find($id);
        if (!$admin) {
            throw new PageNotFoundException();
        }

        $data = [
            'page_title' => lang("Web.edit")." ".lang("Web.master.admin")." " . $admin->nama,
            'admin' => $admin,
            'url_edit' => herauth_web_url($this->root_view . "admin/edit/".$id),
        ];
        return $this->view("admin/edit", $data);
    }

}
