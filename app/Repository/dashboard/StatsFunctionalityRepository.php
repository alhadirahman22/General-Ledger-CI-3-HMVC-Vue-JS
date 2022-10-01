<?php

namespace Repository\dashboard;

use Carbon\Carbon;


use Modules\benda\models\Benda_model_eloquent;
use Illuminate\Database\Capsule\Manager as Capsule;
use Modules\mutasi\models\Mutasi_benda_model_eloquent;

use Modules\dashboard\models\DashboardStats_model_eloquent;
use Modules\dashboard\models\DashboardStatsBendaTagging_model_eloquent;
use Modules\dashboard\models\DashboardStatsCategoryBenda_model_eloquent;
use Modules\dashboard\models\DashboardStatsDepartmentBenda_model_eloquent;


class StatsFunctionalityRepository
{
    protected $CI;
    protected $conditionBaik =  1;
    protected $conditionBuruk =  2;
    protected $dateParam;
    public function __construct($dateParam)
    {
        $this->CI = &get_instance();
        if (!$dateParam) {
            $this->dateParam = date('Y-m-d');
        } else {
            $this->dateParam = $dateParam;
        }
    }

    public function fillDashboard()
    {
        $dateParam = $this->dateParam;
        $data = DashboardStats_model_eloquent::whereDate('created_at', '=', $dateParam)->first();
        Capsule::beginTransaction();
        try {
            $dataSave = [
                'benda_is_good' => $this->benda_is_good(),
                'benda_is_bad' => $this->benda_is_bad(),
                'benda_is_all' => $this->benda_is_all(),
                'pengunjung' => $this->pengunjung(),
                'mutasi_process' => $this->mutasi_process(),
                'ram_used' => $this->ram_used(),
                'ram_total' => $this->ram_total(),
                'benda_last3month' => $this->benda_last3month(),
                'all_user' => $this->CI->db->count_all_results('aauth_users'),
            ];
            if ($data) {
                DashboardStats_model_eloquent::whereDate('created_at', '=', $dateParam)->delete();
            }

            $new = DashboardStats_model_eloquent::create($dataSave);
            $dashboard_stats_id = $new->dashboard_stats_id;

            $this->fillJumlahKoleksi($dashboard_stats_id);
            $this->fillJumlahPenyebaranKoleksi($dashboard_stats_id);
            $this->fillJumlahTagsGrouping($dashboard_stats_id);

            Capsule::commit();
        } catch (\Throwable $th) {
            Capsule::rollback();
            die($th->getMessage());
            return false;
        }

        return true;
    }

    public function benda_last3month()
    {
        $rs = 0;
        // $sx = Carbon::now()->startOfMonth()->subMonth(3);
        // print_r($sx);
        // die();
        try {
            // Capsule::connection()->enableQueryLog();
            $rs = Benda_model_eloquent::whereBetween(
                'created_at',
                [Carbon::now()->subMonth(3), Carbon::now()]
            )->count();
            // $queries = Capsule::getQueryLog();
            // print_r($rs);
            // die();
            return $rs;
        } catch (\Throwable $th) {
            // die($th->getMessage());
        }
        return $rs;
    }

    public function benda_is_good()
    {
        $rs = 0;
        try {
            $rs = Benda_model_eloquent::where('kondisi_id', $this->conditionBaik)->count();
            return $rs;
        } catch (\Throwable $th) {
            //throw $th;
        }
        return $rs;
    }
    public function benda_is_bad()
    {
        $rs = 0;
        try {
            $rs = Benda_model_eloquent::where('kondisi_id', $this->conditionBuruk)->count();
            return $rs;
        } catch (\Throwable $th) {
            //throw $th;
        }
        return $rs;
    }
    public function benda_is_all()
    {
        $rs = 0;
        try {
            $rs = Benda_model_eloquent::count();
            return $rs;
        } catch (\Throwable $th) {
            //throw $th;
        }
        return $rs;
    }
    public function pengunjung()
    {
        $rs = 0;
        try {
            // Capsule::connection()->enableQueryLog();

            $rs = Capsule::table('log')
                ->select(array('id_aauth_users'))
                ->whereDate('accessOn', '=', $this->dateParam)
                ->groupBy('id_aauth_users')
                ->count();
            // $queries = Capsule::getQueryLog();
            // print_r($queries);
            // die();
        } catch (\Throwable $th) {
            //throw $th;
            // die($th->getMessage());
        }
        return $rs;
    }
    public function mutasi_process()
    {
        $rs = 0;
        try {
            $statusMutasiGrouping = [
                '0' => 'Progress Mutasi',
                '2' => 'Progress Mutasi',
            ];

            $status = ['0', '1', '2'];
            $rs = Mutasi_benda_model_eloquent::whereIn('status', $status)->count();
        } catch (\Throwable $th) {
            //throw $th;
        }
        return $rs;
    }
    public function ram_used()
    {
        $rs = 0;
        try {
            $rs = memory_get_usage() / 1024; // in MB
            $rs = (int) $rs;
        } catch (\Throwable $th) {
            //throw $th;
        }
        return $rs;
    }
    public function ram_total()
    {
        $rs = 0;
        try {
            if (strtoupper(substr(PHP_OS, 0, 3)) === 'WIN') {
                exec('wmic memorychip get capacity', $totalMemory);
                $totalMemory =  array_sum($totalMemory) / 1024 / 1024;
                $rs = $totalMemory;
                $rs = (int) $rs;
            } else {
                $fh = fopen('/proc/meminfo', 'r');
                $mem = 0;
                while ($line = fgets($fh)) {
                    $pieces = array();
                    if (preg_match('/^MemTotal:\s+(\d+)\skB$/', $line, $pieces)) {
                        $mem = $pieces[1];
                        break;
                    }
                }
                fclose($fh);

                // echo "$mem kB RAM found";
                $rs = $mem / 1024;
                $rs = (int) $rs;
            }
        } catch (\Throwable $th) {
            //throw $th;
        }
        return $rs;
    }

    public function fillJumlahKoleksi($dashboard_stats_id)
    {
        $q = Capsule::table('kategori')
            ->select(array('kategori_id', Capsule::raw('(select count(*) as total  from bendas where bendas.kategori_id = kategori.kategori_id) as total')))
            ->get()->toArray();

        $c = DashboardStatsCategoryBenda_model_eloquent::where('dashboard_stats_id', $dashboard_stats_id)->get();
        Capsule::beginTransaction();
        try {


            if (count($c) > 0) {
                DashboardStatsCategoryBenda_model_eloquent::where('dashboard_stats_id', $dashboard_stats_id)->delete();
            }

            for ($i = 0; $i < count($q); $i++) {
                $new = new DashboardStatsCategoryBenda_model_eloquent;

                $new->dashboard_stats_id = $dashboard_stats_id;
                $new->kategory_id = $q[$i]->kategori_id;
                $new->total = (int)$q[$i]->total;
                $new->save();
            }
            Capsule::commit();
        } catch (\Throwable $th) {
            Capsule::rollback();
            return false;
        }

        return true;
    }

    public function fillJumlahPenyebaranKoleksi($dashboard_stats_id)
    {
        $q = Capsule::table('departments')
            ->select(array('department_id', Capsule::raw('(select count(*) as total  from bendas where bendas.department_id = departments.department_id) as total')))
            ->get()->toArray();

        $c = DashboardStatsDepartmentBenda_model_eloquent::where('dashboard_stats_id', $dashboard_stats_id)->get();
        Capsule::beginTransaction();
        try {
            if (count($c) > 0) {
                DashboardStatsDepartmentBenda_model_eloquent::where('dashboard_stats_id', $dashboard_stats_id)->delete();
            }

            for ($i = 0; $i < count($q); $i++) {
                $new = new DashboardStatsDepartmentBenda_model_eloquent;
                $new->dashboard_stats_id = $dashboard_stats_id;
                $new->department_id = $q[$i]->department_id;
                $new->total = (int)$q[$i]->total;
                $new->save();
            }
            Capsule::commit();
        } catch (\Throwable $th) {
            Capsule::rollback();
            return false;
        }

        return true;
    }

    public function fillJumlahTagsGrouping($dashboard_stats_id)
    {
        $q = Capsule::table('tags')
            ->select(array('tag_id', Capsule::raw('(select count(*) as total  from benda_tags where benda_tags.tag_id = tags.tag_id) as total')))
            ->get()->toArray();

        $c = DashboardStatsBendaTagging_model_eloquent::where('dashboard_stats_id', $dashboard_stats_id)->get();
        Capsule::beginTransaction();
        try {
            if (count($c) > 0) {
                DashboardStatsBendaTagging_model_eloquent::where('dashboard_stats_id', $dashboard_stats_id)->delete();
            }

            for ($i = 0; $i < count($q); $i++) {
                $new = new DashboardStatsBendaTagging_model_eloquent;
                $new->dashboard_stats_id = $dashboard_stats_id;
                $new->tag_id = $q[$i]->tag_id;
                $new->total = (int)$q[$i]->total;
                $new->save();
            }
            Capsule::commit();
        } catch (\Throwable $th) {
            Capsule::rollback();
            return false;
        }

        return true;
    }
}
