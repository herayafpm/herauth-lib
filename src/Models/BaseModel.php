<?php

namespace Raydragneel\HerauthLib\Models;

use CodeIgniter\Model;

class BaseModel extends Model
{
    protected $DBGroup  = 'default';
    protected $message;

    public function getMessage()
    {
        return $this->message;
    }

    public function setMessage($message)
    {
        $this->message = $message;
    }

    public function setDBGroup($db)
	{
		$this->DBGroup = $db;
        $this->db = db_connect($db,true);
		return $this;
	}

    public function getTable()
    {
        return $this->table;
    }
    public function getTableAs()
    {
        return $this->tableAs;
    }

    public function filter($limit, $start, $order, $ordered, $params = [])
	{
		$builder = $this;
		$order = $this->filterData($order);
		$builder->orderBy($order, $ordered);
		
        if(isset($params['select'])){
            $builder->select($params['select']);
        }else{
            $builder->select("{$this->table}.*");
        }

		if (isset($params['where'])) {
			$where = $params['where'];
			foreach ($where as $key => $value) {
				$pos = strpos($key, '.');
				if($pos === false){
					unset($where[$key]);
					$where["{$this->table}.{$key}"] = $value;
				}
			}
			$builder->where($where);
		}
		if (isset($params['like'])) {
			foreach ($params['like'] as $key => $value) {
				$pos = strpos($key, '.');
				if($pos === false){
					$key = "{$this->table}.{$key}";
				}
				$builder->like($key, $value);
			}
		}
		if (isset($params['orLike'])) {
			foreach ($params['orLike'] as $key => $value) {
				$pos = strpos($key, '.');
				if($pos === false){
					$key = "{$this->table}.{$key}";
				}
				$builder->orLike($key, $value);
			}
		}
        if(isset($params['withDeleted'])){
            $builder->withDeleted();
        }
        if ($limit > 0) {
			return $builder->findAll($limit, $start); // Untuk menambahkan query LIMIT
		}else{
            return $builder->findAll();
        }
	}
    public function count_all($params = [])
	{
		$builder = $this;
		
        if(isset($params['select'])){
            $builder->select($params['select']);
        }else{
            $builder->select("{$this->table}.*");
        }

		if (isset($params['where'])) {
			$where = $params['where'];
			foreach ($where as $key => $value) {
				$pos = strpos($key, '.');
				if($pos === false){
					unset($where[$key]);
					$where["{$this->table}.{$key}"] = $value;
				}
			}
			$builder->where($where);
		}
		if (isset($params['like'])) {
			foreach ($params['like'] as $key => $value) {
				$pos = strpos($key, '.');
				if($pos === false){
					$key = "{$this->table}.{$key}";
				}
				$builder->like($key, $value);
			}
		}
		if (isset($params['orLike'])) {
			foreach ($params['orLike'] as $key => $value) {
				$pos = strpos($key, '.');
				if($pos === false){
					$key = "{$this->table}.{$key}";
				}
				$builder->orLike($key, $value);
			}
		}
        if(isset($params['withDeleted'])){
            $builder->withDeleted();
        }
        return $builder->countAllResults();
	}

    public function filterData($key)
	{
		$key = $this->alias_field[$key] ?? $key;
		$pos = strpos($key, '.');
		if($pos === false){
			$key = "{$this->table}.{$key}";
		}	
		return $key;
	}
}