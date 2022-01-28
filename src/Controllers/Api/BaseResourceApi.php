<?php

namespace Raydragneel\HerauthLib\Controllers\Api;

use CodeIgniter\Config\Services;
use CodeIgniter\RESTful\ResourceController;
use CodeIgniter\HTTP\RequestInterface;
use CodeIgniter\HTTP\ResponseInterface;
use CodeIgniter\Validation\Exceptions\ValidationException;
use Psr\Log\LoggerInterface;
use Raydragneel\HerauthLib\Models\AdminModel;

class BaseResourceApi extends ResourceController
{
    protected $data;
    protected $validator;
    protected $__user = null;
    protected $session = null;
    protected $jenis_akses = 'web';
    protected $helpers = ['herauth_main'];
    public function initController(RequestInterface $request, ResponseInterface $response, LoggerInterface $logger)
    {
        parent::initController($request, $response, $logger);
        $segment = 0;
        if ($request->uri->getSegments()[0] === 'herauth') {
            $segment = 1;
        }
        $this->jenis_akses = $request->uri->getSegments()[$segment];
        $request->jenis_akses = $this->jenis_akses;
        $admin_model = model(AdminModel::class);
        if ($this->jenis_akses === 'web') {
            $this->session = session();
            if($this->session->has('username')){
                if($this->session->has('modelName')){
                    $admin_model = model($this->session->get('modelName'));
                }
                $_user = $admin_model->cekUser($this->session->get('username'));
                $request->_user = $_user;
                $this->__user = $_user;
            }
        }else{
            if($request->__username ?? '' !== ''){
                if(isset($request->__model)){
                    if(!empty($request->__model ?? '')){
                        $admin_model = model($request->__model);
                    }
                }
                $_user = $admin_model->cekUser($request->__username);
                $request->_user = $_user;
                $this->__user = $_user;
            }
        }
    }
    protected function validate($rules, array $messages = []): bool
    {
        $this->validator = Services::validation();
        // If you replace the $rules array with the name of the group
        if (is_string($rules)) {
            $validation = config('Validation');

            // If the rule wasn't found in the \Config\Validation, we
            // should throw an exception so the developer can find it.
            if (!isset($validation->$rules)) {
                throw ValidationException::forRuleNotFound($rules);
            }

            // If no error message is defined, use the error message in the Config\Validation file
            if (!$messages) {
                $errorName = $rules . '_errors';
                $messages  = $validation->$errorName ?? [];
            }

            $rules = $validation->$rules;
        }
        $data = $this->getDataRequest();
        return $this->validator->setRules($rules, $messages)->run((array)$data);
    }
    protected function getDataRequest($filtering = true)
    {
        $request = $this->request;
        /** @var IncomingRequest $request */
        if (strpos($request->getHeaderLine('Content-Type'), 'application/json') !== false) {
            $data = $request->getJSON(true);
        } else {
            if (
                in_array($request->getMethod(), ['put', 'patch', 'delete'], true)
                && strpos($request->getHeaderLine('Content-Type'), 'multipart/form-data') === false
            ) {
                $data = $request->getRawInput();
            } else {
                $data = $request->getVar() ?? [];
            }
        }
        $data = (array) array_merge((array)$data, $request->getFiles() ?? []);
        if ($filtering) {
            return $this->filteringData($data);
        } else {
            return $data;
        }
    }
    protected function filteringData($data)
    {
        foreach ($data as &$value) {
            if (is_string($value)) {
                $value = htmlspecialchars($value, true);
            }
        }
        unset($value);
        return $data;
    }

    protected function datatable_get($params = [],$model = null)
    {
        if($model === null){
            $model = $this->model;
        }
        $rules = [
            'length' => [
                'label'  => "Length",
                'rules'  => "required",
                'errors' => []
            ],
            'start' => [
                'label'  => "Start",
                'rules'  => "required",
                'errors' => []
            ],
            'order' => [
                'label'  => "Order",
                'rules'  => "required",
                'errors' => []
            ],
            'columns' => [
                'label'  => "Columns",
                'rules'  => "required",
                'errors' => []
            ],
        ];

        if (!$this->validate($rules)) {
            $this->response->setStatusCode(400)->setJSON(["status" => false, "message" => lang("Validation.errorValidation"), "data" => $this->validator->getErrors()])->send();
            die();
        }
        $data = $this->getDataRequest();
        $limit = $data['length']; // Ambil data limit per page
        $start = $data['start']; // Ambil data start
        $order_index = $data['order'][0]['column']; // Untuk mengambil index yg menjadi acuan untuk sorting
        $orderBy = $data['columns'][$order_index]['data']; // Untuk mengambil nama field yg menjadi acuan untuk sorting
        $ordered = $data['order'][0]['dir']; // Untuk menentukan order by "ASC" atau "DESC"
        $sql_total = $model->count_all($params); // Panggil fungsi count_all pada Admin
        $sql_data = $model->filter($limit, $start, $orderBy, $ordered, $params); // Panggil fungsi filter pada Admin
        $sql_filter = $model->count_all($params); // Panggil fungsi count_filter pada Admin
        $callback = [
            'draw' => $data['draw'], // Ini dari datatablenya
            'recordsTotal' => $sql_total,
            'recordsFiltered' => $sql_filter,
            'data' => $sql_data
        ];
        return $callback;
    }
}
