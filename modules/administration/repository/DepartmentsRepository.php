<?php

namespace Modules\administration\repository;

use Modules\administration\models\Departments_model_eloquent;

class DepartmentsRepository
{
    protected $CI;
    public function __construct()
    {
        $this->CI = &get_instance();
    }

    public function get($where = null)
    {
        $data = new Departments_model_eloquent;
        $data = $data->with('company');
        if ($where != null) {
            foreach ($where as $key => $value) {
                $data = $data->where($key, $value);
            }
            return $data->get()->toArray();
        } else {
            return $data->get()->toArray();
        }
    }

    public function getCompanyID($department_id)
    {
        $data = Departments_model_eloquent::find($department_id);
        if ($data) {
            return  $data->company_id;
        } else {
            return null;
        }
    }
}
