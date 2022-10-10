<?php

namespace Modules\administration\repository;

use Modules\administration\models\Employee_model_eloquent;


class EmployeeRepository
{
    protected $CI;
    public function __construct()
    {
        $this->CI = &get_instance();
    }

    public function get($where = null)
    {
        $data = new Employee_model_eloquent;
        $data = $data->with('department', 'jabatan');
        if ($where != null) {
            foreach ($where as $key => $value) {
                $data = $data->where($key, $value);
            }
            return $data->get()->toArray();
        } else {
            return $data->get()->toArray();
        }
    }
}
