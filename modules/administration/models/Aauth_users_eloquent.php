<?php
defined('BASEPATH') or exit('No direct script access allowed');
// namespace Contact_management\Models;
use Illuminate\Database\Eloquent\Model as Eloquent;

$CI = &get_instance();
// if (!class_exists('Aauth_user_to_group_model_eloquent')) {
//     $CI->load->model('administration/Aauth_user_to_group_model_eloquent');
// }

if (!class_exists('Aauth_groups_model_eloquent')) {
    $CI->load->model('administration/Aauth_groups_model_eloquent');
}

class Aauth_users_eloquent extends Eloquent
{
    protected $table = 'aauth_users';
    protected $primaryKey = 'id';
    public $timestamps = false;

    public function aauth_groups()
    {
        // return $this->hasOne(Aauth_user_to_group_model_eloquent::class, 'user_id', 'id');
        return $this->belongsToMany(Aauth_groups_model_eloquent::class, 'aauth_user_to_group', 'user_id', 'group_id');
    }
}
