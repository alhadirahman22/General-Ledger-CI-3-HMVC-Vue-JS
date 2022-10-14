<?php

defined('BASEPATH') or exit('No direct script access allowed');

class M_master extends CI_Model
{

    function __construct()
    {
        parent::__construct();
    }

    public function testcall()
    {
        print_r('model called');
    }

    public function getInputToken()
    {
        $token = $this->input->post('token');
        // $data_arr = (array) $this->jwt->decode($token,jwtKey);
        // $data_arr = json_decode(json_encode($data_arr),true);
        $data_arr = $this->decode_token($token);
        return $this->re_format_POST_serializeArray_to_Validation_CI($data_arr);
    }

    public function decode_token($token)
    {
        $data_arr = (array) $this->jwt->decode($token, jwtKey);
        $data_arr = json_decode(json_encode($data_arr), true);
        return $data_arr;
    }

    public function re_format_POST_serializeArray_to_Validation_CI($data)
    {
        $rs = [];
        $skip_arr = [];
        $str_array = '[]';

        for ($i = 0; $i < count($data); $i++) {
            if (in_array($i, $skip_arr)) {
                continue;
            }
            if (!array_key_exists('name', $data[$i])) {
                die('Format not match, ex : Array ( [0] => Array ( [name] => ID_kelompok_profesi [value] => ) [1] => Array ( [name] => Name [value] => fgdgfdg ) )');
            } else {
                $boolFind = false;
                $name = str_replace($str_array, '', $data[$i]['name']);
                $arr_v = [];
                for ($j = $i + 1; $j < count($data); $j++) {
                    if ($data[$i]['name'] == $data[$j]['name']) {
                        $boolFind = true;
                        break;
                    }
                }

                if ($boolFind) {

                    $arr_v[] =  $data[$i]['value'];
                    $skip_arr[] = $i;
                    for ($j = $i + 1; $j < count($data); $j++) {
                        if ($data[$i]['name'] == $data[$j]['name']) {
                            $arr_v[] =  $data[$j]['value'];
                            $skip_arr[] = $j;
                        }
                    }

                    $rs[$name] = $arr_v;
                } else {
                    $rs[$data[$i]['name']] = $data[$i]['value'];
                }
            }
        }

        return $rs;
    }

    function gets($table, $conditions = array(), $order = NULL, $group = NULL, $limit = NULL)
    {
        if ($order) {
            $this->db->order_by($order);
        }
        if ($group) {
            $this->db->group_by($group);
        }
        if ($limit) {
            $this->db->limit($limit);
        }
        if (count($conditions) > 0) {
            $query = $this->db->get_where($table, $conditions);
        } else {
            $query = $this->db->get($table);
        }
        if ($query->num_rows() > 0) {
            return $query;
        } else {
            return false;
        }
    }

    function gets_paging($table, $from, $max, $condition = array(), $sort = '', $order = '')
    {
        if ($condition) {
            $this->db->where($condition);
        }
        if ($sort && $order) {
            $this->db->order_by($sort, $order);
        }
        $this->db->limit($max, $from);
        $query = $this->db->get($table);
        return ($query->num_rows() > 0) ? $query : false;
    }

    function get($table, $conditions = array())
    {
        $query = $this->db->get_where($table, $conditions);
        if ($query->num_rows() > 0) {
            return $query->row();
        } else {
            return false;
        }
    }

    function insert($table, $data = array())
    {
        $query = $this->db->insert($table, $data);
        return ($query) ? $this->db->insert_id() : false;
    }

    function replace($table, $data = array())
    {
        $query = $this->db->replace($table, $data);
        return ($query) ? true : false;
    }

    function update($table, $data = array(), $condition = array())
    {
        $this->db->where($condition);
        return $this->db->update($table, $data);
    }

    function delete($table, $condition = array())
    {
        $this->db->where($condition);
        $this->db->delete($table);
        return ($this->db->affected_rows() > 0) ? true : false;
    }

    function count($table, $condition = array())
    {
        if ($condition) {
            $this->db->where($condition);
        }
        return $this->db->count_all_results($table);
    }

    function truncate($table)
    {
        $query = $this->db->truncate($table);
        return ($query) ? true : false;
    }

    public function auth_apps()
    {
        $Bool = false;
        $getallheaders = getallheaders();
        foreach ($getallheaders as $name  => $value) {
            if ($name == 'X-Key' && $value == settings('key_auth')) {
                $Bool = true;
                break;
            }
        }
        return $Bool;
    }

    public function table_exist($tableName)
    {
        $d =  $this->db->query(
            '
                SELECT * 
                FROM information_schema.tables
                WHERE table_schema = "' . $this->db->database . '" 
                    AND table_name = "' . $tableName . '"
                LIMIT 1;
            '
        )->result_array();

        if (count($d) > 0) {
            return true;
        } else {
            return false;
        }
    }

    public function AlphabetColExcelNumber($ColNumber)
    {
        $string = '';
        $keyM = array('A', 'B', 'C', 'D', 'E', 'F', 'G', 'H', 'I', 'J', 'K', 'L', 'M', 'N', 'O', 'P', 'Q', 'R', 'S', 'T', 'U', 'V', 'W', 'X', 'Y', 'Z');
        $bool = true;
        $inc = 0;

        while ($bool) {
            $str = '';
            for ($i = 0; $i < count($keyM); $i++) {
                if ($inc == $ColNumber) {
                    $string = $str . $keyM[$i];
                    $bool = false;
                    break;
                }
                $t = $i + 1;
                if ($t >= count($keyM)) {
                    $i = -1;
                    if ($str == '') {
                        $str = $keyM[0];
                    } else {
                        $lengthString = strlen($str);
                        $bool2 = true;
                        for ($j = -1; $j < $lengthString; $j--) {
                            $min = '-' . $lengthString;
                            $min = (int) $min;
                            $lastchars = substr($str, $j, 1);
                            for ($k = 0; $k < count($keyM); $k++) {
                                $t = $keyM[$k];
                                if ($lastchars ==  $t) {
                                    if (($k + 1) == count($keyM)) {
                                        //cek lenght string yang diupdate bagian mana
                                        $bool3 = true;
                                        for ($l = 0; $l < $lengthString; $l++) {
                                            if (substr($str, $l, 1) != $keyM[$k]) {
                                                $bool3 = false;
                                                break;
                                            }
                                        }

                                        $upd = $keyM[0];
                                        $str = substr($str, 0, ($lengthString + $j)) . $upd . substr($str, ($lengthString + $j + 1), $lengthString);

                                        $bool4 = true;
                                        for ($l = 0; $l < strlen($str); $l++) {
                                            if (substr($str, $l, 1) != $keyM[0]) {
                                                $bool4 = false;
                                                break;
                                            }
                                        }

                                        if (($bool3 || $bool4) && $j == $min) {
                                            $str = $keyM[0] . $str;
                                        }
                                    } else {
                                        $upd = $keyM[($k + 1)];
                                        $str = substr($str, 0, ($lengthString + $j)) . $upd . substr($str, ($lengthString + $j + 1), $lengthString);
                                        $bool2 = false;
                                    }
                                    break;
                                }
                            }

                            if ($min == $j || (!$bool2)) {
                                break;
                            }
                        }
                    }
                }
                $inc++;
            }
        }

        return $string;
    }

    // public function fnEncrypt($sValue = 'Klartext', $sSecretKey = 'XaCs!0rt' . "\0\0\0\0\0\0\0\0")
    // {
    //     return rtrim(
    //         base64_encode(
    //             mcrypt_encrypt(
    //                 MCRYPT_RIJNDAEL_256,
    //                 $sSecretKey,
    //                 $sValue,
    //                 MCRYPT_MODE_ECB,
    //                 mcrypt_create_iv(
    //                     mcrypt_get_iv_size(
    //                         MCRYPT_RIJNDAEL_256,
    //                         MCRYPT_MODE_ECB
    //                     ),
    //                     MCRYPT_RAND
    //                 )
    //             )
    //         ),
    //         "\0"
    //     );
    // }

    // public function fnDecrypt($sValue, $sSecretKey = 'XaCs!0rt' . "\0\0\0\0\0\0\0\0")
    // {
    //     return rtrim(
    //         mcrypt_decrypt(
    //             MCRYPT_RIJNDAEL_256,
    //             $sSecretKey,
    //             base64_decode($sValue),
    //             MCRYPT_MODE_ECB,
    //             mcrypt_create_iv(
    //                 mcrypt_get_iv_size(
    //                     MCRYPT_RIJNDAEL_256,
    //                     MCRYPT_MODE_ECB
    //                 ),
    //                 MCRYPT_RAND
    //             )
    //         ),
    //         "\0"
    //     );
    // }

    public function saveLog()
    {
        if (settings('log_active') == 1) {
            $request = $_REQUEST;
            // $ip1 = $this->input->ip_address();

            $ip = $_SERVER["REMOTE_ADDR"];
            if (filter_var(@$_SERVER['HTTP_X_FORWARDED_FOR'], FILTER_VALIDATE_IP)) {
                $ip = $_SERVER['HTTP_X_FORWARDED_FOR'];
            }

            if (filter_var(@$_SERVER['HTTP_CLIENT_IP'], FILTER_VALIDATE_IP)) {
                $ip = $_SERVER['HTTP_CLIENT_IP'];
            }

            $dataSave = [
                'id_aauth_users' => $this->session->userdata('id'),
                'uri' => $_SERVER['REQUEST_URI'],
                'request' => json_encode($request),
                'header' => json_encode(getallheaders()),
                'accessOn'  => Date('Y-m-d H:i:s'),
                'ip1' => $ip
            ];

            $this->db->insert('log', $dataSave);
        }
    }

    public function upload_file($config, $fileDo)
    {
        $this->lang->load('form_validation');
        $rs = ['status' => 0, 'msg' => ''];
        $this->load->library('upload', $config);

        if (!$this->upload->do_upload($fileDo)) {
            $rs['msg'] =  $this->upload->display_errors();
        } else {
            $data = array('upload_data' => $this->upload->data());

            $rs['status'] = 1;
        }

        return $rs;
    }

    public function form_set_all_trim($formData)
    {
        foreach ($formData as $key => $value) {
            $formData[$key] = trim($value);
        }

        return $formData;
    }

    public function generate_item_code($item_category_id)
    {
        // get prefix
        $dataPrefix = $this->db->select('prefix')->get_where('item_category', array(
            'item_category_id' => $item_category_id
        ))->result_array();

        $result = '';
        if (count($dataPrefix) > 0) {
            $Pref_1 = $dataPrefix[0]['prefix'];
            $pref_2 = Date('m.Y');
            $prefix = $Pref_1 . '.' . $pref_2 . '.';
            $pref_3 = $this->db->like('item_code', $prefix, 'both')->count_all_results('item');

            // print_r(strlen($pref_3 + 1));
            // die();
            $pad = 5;
            if (strlen($pref_3 + 1) > 4 && strlen($pref_3 + 1) <= 6) {
                $pad = 7;
            } else if (strlen($pref_3 + 1) > 6 && strlen($pref_3 + 1) <= 8) {
                $pad = 9;
            } else if (strlen($pref_3 + 1) > 8 && strlen($pref_3 + 1) <= 10) {
                $pad = 11;
            }

            $last_pref = str_pad(($pref_3 + 1), $pad, "0", STR_PAD_LEFT);
            $result = $prefix . $last_pref;
        }

        return $result;
    }

    public function replace_mask_money($money)
    {
        $money =  str_replace('.', '', $money);
        $strPos = strPos($money, ',');
        if ($strPos) {
            $money = substr($money, 0, $strPos);
        }

        //print_r($money);die();
        return $money;
    }

    public function set_environtment()
    {
        if (!defined('ENVIRONMENT_SETTING')) {
            if (settings('env_debug') == 'development') {
                define('ENVIRONMENT_SETTING', 'development');
            } else {
                define('ENVIRONMENT_SETTING', 'production');
            }

            switch (ENVIRONMENT_SETTING) {
                case 'development':
                    error_reporting(-1);
                    ini_set('display_errors', 1);
                    break;

                case 'testing':
                case 'production':
                    ini_set('display_errors', 0);
                    if (version_compare(PHP_VERSION, '5.3', '>=')) {
                        error_reporting(E_ALL & ~E_NOTICE & ~E_DEPRECATED & ~E_STRICT & ~E_USER_NOTICE & ~E_USER_DEPRECATED);
                    } else {
                        error_reporting(E_ALL & ~E_NOTICE & ~E_STRICT & ~E_USER_NOTICE);
                    }
                    break;

                default:
                    header('HTTP/1.1 503 Service Unavailable.', TRUE, 503);
                    echo 'The application environment is not set correctly.';
                    exit(1); // EXIT_ERROR
            }
        }
    }

    public function get_username_by($userID)
    {
        $data =  $this->db->where('a.id', $userID)->join('employees AS b', 'b.employee_id = a.employee_id', 'left')
            ->select('a.*, b.name AS employee_name')->get('aauth_users AS a')->row();
        $rs = '';
        if (!empty($data)) {
            $rs = ($data->employee_id != null && $data->employee_id != 'null') ? $data->employee_name : $data->username;
        }

        return $rs;
    }

    public function get_dropdown($table, $fieldValue, $fieldText, $all = true, $where = '', $empty = true, $order_by = '', $order_act = '')
    {
        if ($all) {
            $rs = ['' => 'All'];
        } else {
            $rs = [];
        }

        if ($empty) {
            $rs = ['' => 'Nothing selected'];
        } else {
            $rs = [];
        }


        if (!empty($order_by)) {
            $this->db->order_by($order_by, $order_act);
        }

        if (!empty($where)) {
            $this->db->where($where);
        }

        // $this->db->where('active', 1);

        $d = $this->db->get($table)->result_array();


        for ($i = 0; $i < count($d); $i++) {
            $rs[$d[$i][$fieldValue]] = $d[$i][$fieldText];
        }

        return $rs;
    }

    public function get_dropdown_filter_museum($table, $fieldValue, $fieldText, $all = true, $where = '', $empty = true, $order_by = '', $order_act = '')
    {
        if ($all) {
            $rs = ['' => 'All'];
        } else {
            $rs = [];
        }

        if ($empty) {
            $rs = ['' => 'Nothing selected'];
        } else {
            $rs = [];
        }


        if (!empty($order_by)) {
            $this->db->order_by($order_by, $order_act);
        }

        if (!empty($where)) {
            $this->db->where($where);
        }

        $this->db->where('active', 1);

        $museums = $this->session->userdata('user')->museums;
        if (count($museums) > 0) {
            $list_museum = array();
            for ($g = 0; $g < count($museums); $g++) {
                array_push($list_museum, $museums[$g]->museum_id);
            }
            $this->db->where_in('museum_id', $list_museum);
        }

        $d = $this->db->get($table)->result_array();


        for ($i = 0; $i < count($d); $i++) {
            $rs[$d[$i][$fieldValue]] = $d[$i][$fieldText];
        }

        return $rs;
    }

    public function set_formTab_Active_validation($field_data_validation)
    {
        $rs = [];

        foreach ($field_data_validation as $key => $value) {

            if (!empty($value['error'])) {
                $rs[] = $key;
            }
        }

        return $rs;
    }

    public function _array_get_list($arr, $takingField)
    {
        $rs = [];
        for ($i = 0; $i < count($arr); $i++) {
            if (isset($arr[$i][$takingField])) {
                $rs[] = $arr[$i][$takingField];
            }
        }

        return $rs;
    }

    public function _addCreated($dataSave)
    {
        for ($i = 0; $i < count($dataSave); $i++) {
            $dataSave[$i]['created_at'] = getNOW();
            $dataSave[$i]['created_by'] = $this->data['user']->id;
        }

        return $dataSave;
    }

    public function load_select2($search, $table, $arrField = ['id' => 'employee_id', 'text' => 'nama'], $addDefaultValue = [])
    {
        $rs = [];
        $d = $this->db->like($arrField['text'], $search, 'after')->get($table)->result_array();
        for ($i = 0; $i < count($d); $i++) {
            $temp = [
                'id' => $d[$i][$arrField['id']],
                'text' => $d[$i][$arrField['text']],
            ];

            $rs[] = $temp;
        }

        if (!empty($addDefaultValue)) {
            $rs[] = $addDefaultValue;
        }

        return $rs;
    }

    public function arr_object_to_html($arrMultidimensi)
    {
        $html = '';
        if (is_array($arrMultidimensi) && count($arrMultidimensi) > 0) {
            $html = '<ul>';
            $arrMultidimensi =  json_decode(json_encode($arrMultidimensi), true);
            for ($i = 0; $i < count($arrMultidimensi); $i++) {
                $row = $arrMultidimensi[$i];
                $html .= '<ul>';
                foreach ($row as $key => $value) {
                    if (is_array($value)) {
                        $value = '<pre>' . print_r($value, true) . '</pre>';
                    }
                    $html .= '<li>"' . $key . '" : "' . $value . '"</li>';
                }
                $html .= '</ul>';
            }
            $html .= '</ul>';
        }

        return $html;
    }

    public function object_to_html($row)
    {
        $row =  json_decode(json_encode($row), true);
        $html = '';
        $html .= '<ul>';
        foreach ($row as $key => $value) {
            if (is_array($value)) {
                $value = '<pre>' . print_r($value, true) . '</pre>';
            }
            $html .= '<li>"' . $key . '" : "' . $value . '"</li>';
        }
        $html .= '</ul>';
        return $html;
    }

    public function validationMultiple($data)
    {
        $html = '';
        $html .= '<ul>';
        foreach ($data as $key => $value) {
            $html .= '<li>' . $value . '</li>';
        }

        return $html;
    }

    public function array_duplicate($arrays, $field = [])
    {
        $bool = true;
        foreach ($arrays as $current_key => $current_array) {
            if (count($field) === 0) {
                $search_key = array_search($current_array, $arrays);
                // echo "current key: $current_key \n";
                // echo "search key: $search_key \n";
                if ($current_key != $search_key) {
                    // echo "duplicate found for item $current_key\n";
                    $bool = false;
                    break;
                }
                // echo "\n";
            } else {
                //echo "current key: $current_key\n";
                foreach ($arrays as $search_key => $search_array) {
                    for ($i = 0; $i < count($field); $i++) {
                        if ($search_array[$field[$i]] == $current_array[$field[$i]]) {
                            if ($search_key != $current_key) {
                                // echo "duplicate found: $search_key\n";
                                $bool = false;
                            }
                        }
                    }

                    if (!$bool) {
                        break;
                    }
                }
                // echo "\n";
            }
        }

        return $bool;
    }

    public function getselect2customer($term)
    {
        $where = 'active = 1 AND ( username LIKE "%' . $term . '%" 
        OR name LIKE "%' . $term . '%" 
        OR email LIKE "%' . $term . '%"
        OR handphone LIKE "%' . $term . '%" )';
        $data = $this->db
            ->where($where)
            ->get('customers')->result_array();


        $result = [];

        if (count($data) > 0) {
            for ($i = 0; $i < count($data); $i++) {
                array_push($result, array(
                    'id' => $data[$i]['customer_id'],
                    'text' => $data[$i]['name']
                ));
            }
        }

        return $result;
    }

    public function encodeToPropVue($data)
    {
        return  htmlentities(json_encode($data, JSON_HEX_QUOT), ENT_QUOTES);
    }
}
