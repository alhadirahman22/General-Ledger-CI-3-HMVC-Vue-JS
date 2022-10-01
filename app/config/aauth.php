<?php
defined('BASEPATH') or exit('No direct script access allowed');
$config_aauth = array();

$config_aauth["default"] = array(
    'no_permission'                  => FALSE,

    'admin_group'                    => 'superadmin',
    'default_group'                  => 'admin',
    'public_group'                   => TRUE,

    'db_profile'                     => 'default',

    'users'                          => 'aauth_users',
    'groups'                         => 'aauth_groups',
    'group_to_group'                 => 'aauth_group_to_group',
    'user_to_group'                  => 'aauth_user_to_group',
    'perms'                          => 'aauth_perms',
    'perm_to_group'                  => 'aauth_perm_to_group',
    'perm_to_user'                   => 'aauth_perm_to_user',
    'pms'                            => '',
    'user_variables'                 => '',
    'login_attempts'                 => 'aauth_login_attempts',

    'remember'                       => ' +3 month',

    'max'                            => 13,
    'min'                            => 5,

    'additional_valid_chars'         => array(),

    'ddos_protection'                => true,

    'recaptcha_active'               => false,
    'recaptcha_login_attempts'       => 4,
    'recaptcha_siteKey'              => '',
    'recaptcha_secret'               => '',

    'totp_active'                    => false,
    'totp_only_on_ip_change'         => false,
    'totp_reset_over_reset_password' => false,
    'totp_two_step_login_active'     => false,
    'totp_two_step_login_redirect'   => '/account/twofactor_verification/',

    'max_login_attempt'              => 10,
    'max_login_attempt_time_period'  => "5 minutes",
    'remove_successful_attempts'     => true,

    'login_with_name'                => true,

    'email'                          => 'alhadirahman22@gmail.com',
    'name'                           => 'alhadirahman22',
    'email_config'                   => false,

    'verification'                   => false,
    'verification_link'              => '/account/verification/',
    'reset_password_link'            => '/account/reset_password/',

    'hash'                           => 'sha256',
    'use_password_hash'              => true,
    'password_hash_algo'             => PASSWORD_BCRYPT,
    'password_hash_options'          => array(),

    'pm_encryption'                  => false,
    'pm_cleanup_max_age'             => "3 months",
);

$config['aauth'] = $config_aauth['default'];

/* End of file aauth.php */
/* Location: ./application/config/aauth.php */
