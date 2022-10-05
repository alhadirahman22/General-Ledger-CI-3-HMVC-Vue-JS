<?php

namespace Modules\administration\repository;

use Modules\administration\models\Jabatan_model_eloquent;
use Modules\administration\models\Employee_model_eloquent;

class JabatanRepository
{
    protected $CI;
    public function __construct()
    {
        $this->CI = &get_instance();
    }

    public function getJabatanByDepartment($department_id)
    {
        $data = Jabatan_model_eloquent::with(['employee' => function ($q) use ($department_id) {
            $q->whereHas('department', function ($q) use ($department_id) {
                $q->where('jabatan_department_employee.department_id', $department_id);
            });
        }])->whereHas('department', function ($q) use ($department_id) {
            $q->where('jabatan_department_employee.department_id', $department_id);
        })
            ->get()->toArray();

        return $data;
    }

    public function getEmployee($jabatan_id, $department_id)
    {

        $data = Employee_model_eloquent::whereHas('department', function ($q) use ($department_id) {
            $q->where('jabatan_department_employee.department_id', $department_id);
        })->whereHas('jabatan', function ($q) use ($jabatan_id) {
            $q->where('jabatan_department_employee.jabatan_id', $jabatan_id);
        })->first();
        if ($data) {
            return $data;
        } else {
            return null;
        }
    }

    public function get($where = null)
    {
        $data = new Jabatan_model_eloquent;
        $data = $data->with('getDepartment', 'company');
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
