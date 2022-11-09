<?php

namespace Modules\reimbursment\event;

use Illuminate\Database\Capsule\Manager as Capsule;
use Modules\reimbursment\repository\ReimbursmentRepository;

class Reimbursment_event
{
    protected $CI;
    protected $fromTrigger;
    protected $dataTrigger;

    public function __construct($TriggerName, $dataTrigger)
    {
        $this->CI = &get_instance();
        $this->fromTrigger = $TriggerName;
        $this->dataTrigger = $dataTrigger;
        $this->run();
    }

    public function run()
    {
        switch ($this->fromTrigger) {
            case 'GeneralLedger':
                $upd = $this->fromGL();
                break;

            default:
                return false;
                break;
        }

        return true;
    }

    public function fromGL()
    {
        $ReimbursmentRepository = new ReimbursmentRepository();
        $paid = $ReimbursmentRepository->paid($this->dataTrigger);

        if (!$paid) {
            return false;
        }

        return true;
    }
}
