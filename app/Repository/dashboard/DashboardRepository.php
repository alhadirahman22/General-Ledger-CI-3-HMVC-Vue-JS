<?php

namespace Repository\dashboard;

use Repository\dashboard\StatsFunctionalityRepository;
use Modules\dashboard\models\DashboardStats_model_eloquent;


class DashboardRepository
{
    protected $CI;
    protected $table =  'dashboard_stats';
    protected $bendaBaik =  'benda_is_good';
    protected $bendaBuruk =  'benda_is_bad';
    protected $bendaAll =  'benda_is_all';

    protected $timeLower;
    protected $timeHigher;

    public function __construct()
    {
        $this->CI = &get_instance();

        $this->timeLower = strtotime('09:00:00');
        $this->timeHigher = strtotime('16:30:00');
    }

    public function jumlahKoleksi($dashboard_stats_id)
    {
        $rs = [];
        // 4. Lu ambil aja dare tabel kategori masternya

        return $rs;
    }

    public function jumlahPenyebaranKoleksi($dashboard_stats_id)
    {
        $rs = [];
        // 5. Ambil dari Departemen id yg di benda

        return $rs;
    }

    public function jumlahTagsGrouping($dashboard_stats_id)
    {
        $rs = [];
        // 6. Ambil dari tabel tags kalo ngga salah namanya
        // Apa benda_tags gitu

        return $rs;
    }

    public function updateAllTable($manually = false, $dateNow = false)
    {
        if (!$dateNow) {
            $dateNow = date('Y-m-d');
        }

        $data = DashboardStats_model_eloquent::whereDate('created_at', '=', $dateNow)->first();
        if (!$data || $manually) {
            $StatsFunctionalityRepository = new StatsFunctionalityRepository($dateNow);

            $StatsFunctionalityRepository->fillDashboard();
        }
    }

    public function getDashboard($dateNow = false)
    {
        if (!$dateNow) {
            $dateNow = date('Y-m-d');
        }

        $data = DashboardStats_model_eloquent::whereDate('created_at', '=', $dateNow)->first();

        $timeNow = strtotime(date('H:i:s'));

        $boolHolidayday = (date('D') == 'Sat' || date('D') == 'Sun') ? true : false;
        $getLastData = false;

        if (!$data) {
            if ($timeNow <= $this->timeLower || $timeNow >= $this->timeHigher) {
                $this->updateAllTable(true, $dateNow);
            } else {
                if ($boolHolidayday) {
                    $this->updateAllTable(false, $dateNow);
                } else {
                    $getLastData =  true;
                }
            }
        }

        // $newData = DashboardStats_model_eloquent::whereDate('created_at', '=', $dateNow)->first();

        if (!$getLastData) {
            $rs = DashboardStats_model_eloquent::whereDate('created_at', '=', $dateNow)->with('benda_category.kategori', 'benda_department.department.museum', 'benda_tagging.tagging')->first();
        } else {
            $rs = DashboardStats_model_eloquent::with('benda_category.kategori', 'benda_department.department.museum', 'benda_tagging.tagging')->orderBy('dashboard_stats_id', 'desc')->limit(1)->first();
        }


        $memoryPercent = $rs->ram_used / $rs->ram_total * 100;
        $rs->memoryPercent = (int) $memoryPercent;

        $ramRemaining = ($rs->ram_total - $rs->ram_used); // in mb
        $rs->ramRemaining =  $ramRemaining;
        return $rs;
    }
}
