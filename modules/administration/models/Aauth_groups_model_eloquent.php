<?php
defined('BASEPATH') or exit('No direct script access allowed');
// namespace Contact_management\Models;
use Illuminate\Database\Eloquent\Model as Eloquent;

$CI = &get_instance();
if (!class_exists('Aauth_users_eloquent')) {
    $CI->load->model('administration/Aauth_users_eloquent');
}

class Aauth_groups_model_eloquent extends Eloquent
{
    protected $table = 'aauth_groups';
    protected $primaryKey = 'id';
    public $timestamps = false;

    public function aauth_users()
    {
        // return $this->hasMany(Aauth_user_to_group_model_eloquent::class, 'group_id', 'id');
        return $this->belongsToMany(Aauth_groups_model_eloquent::class, 'aauth_user_to_group', 'group_id', 'user_id');
    }
}
