<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

/* E-mail Messages */

// Account verification
$lang['aauth_email_verification_subject'] = 'Verifikasi akun';
$lang['aauth_email_verification_code'] = 'Kode verifikasi Anda adalah : ';
$lang['aauth_email_verification_text'] = " Anda juga dapat mengklik (atau menyalin dan menempel) link berikut\n\n";

// Password reset
$lang['aauth_email_reset_subject'] = 'Atur Ulang Kata Sandi';
$lang['aauth_email_reset_text'] = "Untuk mengatur ulang kata sandi Anda, klik (atau salin dan tempel alamat di browser Anda) tautan di bawah ini:\n\n";

// Password reset success
$lang['aauth_email_reset_success_subject'] = 'Reset Kata Sandi Berhasil';
$lang['aauth_email_reset_success_new_password'] = 'Kata sandi Anda telah berhasil disetel ulang. Kata sandi baru Anda adalah : ';


/* Error Messages */

// Account creation errors
$lang['aauth_error_email_exists'] = 'Alamat email sudah terdaftar. Jika Anda lupa kata sandi, Anda dapat mengklik tautan di bawah ini.';
$lang['aauth_error_username_exists'] = "Nama akun sudah terfaftar. Silakan masukkan nama pengguna yang berbeda, atau jika Anda lupa kata sandi Anda, silakan klik tautan di bawah ini.";
$lang['aauth_error_email_invalid'] = 'Alamat email salah';
$lang['aauth_error_password_invalid'] = 'Kata sandi salah';
$lang['aauth_error_username_invalid'] = 'Nama pengguna tidak valid';
$lang['aauth_error_username_required'] = 'Nama pengguna diperlukan';
$lang['aauth_error_totp_code_required'] = 'Kode Otentikasi diperlukan';
$lang['aauth_error_totp_code_invalid'] = 'Kode Otentikasi Tidak Valid';


// Account update errors
$lang['aauth_error_update_email_exists'] = 'Alamat email sudah terdaftar. Harap masukkan alamat email yang berbeda.';
$lang['aauth_error_update_username_exists'] = "Nama pengguna sudah terdaftar. Harap masukkan nama pengguna yang berbeda.";


// Access errors
$lang['aauth_error_no_access'] = 'Maaf, Anda tidak memiliki akses yang Anda minta.';
$lang['aauth_error_login_failed_email'] = 'Alamat Email dan Kata Sandi tidak cocok.';
$lang['aauth_error_login_failed_name'] = 'Nama pengguna dan kata sandi tidak cocok.';
$lang['aauth_error_login_failed_all'] = 'Email, Username atau Password tidak cocok.';
$lang['aauth_error_login_attempts_exceeded'] = 'Anda telah melampaui percobaan login, akun Anda sekarang telah dikunci.';
$lang['aauth_error_recaptcha_not_correct'] = 'Maaf, teks reCAPTCHA yang dimasukkan salah.';

// Misc. errors
$lang['aauth_error_no_user'] = 'pengguna tidak ada';
$lang['aauth_error_account_not_verified'] = 'Akun Anda belum diverifikasi. Silakan periksa email Anda dan verifikasi akun Anda.';
$lang['aauth_error_no_group'] = 'Grup tidak ada';
$lang['aauth_error_no_subgroup'] = 'Subgrup tidak ada';
$lang['aauth_error_self_pm'] = 'Tidak bisa mengirim pesan ke diri anda sendiri.';
$lang['aauth_error_no_pm'] = 'Pesan Pribadi tidak ditemukan';
$lang['aauth_error_no_perm'] = 'Izin tidak tersedia';


/* Info messages */
$lang['aauth_info_already_member'] = 'Pengguna sudah menjadi anggota grup';
$lang['aauth_info_already_subgroup'] = 'Subgrup sudah menjadi anggota grup';
$lang['aauth_info_group_exists'] = 'Nama grup sudah ada';
$lang['aauth_info_perm_exists'] = 'Nama izin sudah ada';
