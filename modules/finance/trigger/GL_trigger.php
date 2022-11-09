<?php

namespace Modules\finance\trigger;

use Modules\reimbursment\event\Reimbursment_event;
use Illuminate\Database\Capsule\Manager as Capsule;

class GL_trigger
{
    protected $CI;
    protected $triggerEvent;
    protected $TriggerName = 'GeneralLedger';
    public function __construct($triggerEvent)
    {
        $this->CI = &get_instance();
        $this->triggerEvent = $triggerEvent;
        $this->run();
    }

    public function run()
    {
        try {
            switch ($this->triggerEvent['menuTab']) {
                case 'Reimbursment':
                    $Reimbursment_event = new Reimbursment_event($this->TriggerName, $this->triggerEvent);
                    if (!$Reimbursment_event) {
                        return false;
                    }
                    break;

                default:
                    return false;
                    break;
            }
        } catch (\Throwable $th) {
            return false;
        }

        return true;
    }

    // public function updReimbursment()
    // {
    //     $triggerEvent = $this->triggerEvent;
    //     $getData = $triggerEvent['namespace']::where($triggerEvent['fieldToFind'], $triggerEvent[$triggerEvent['fieldToFind']]);
    //     print_r($getData->first()->toArray());
    //     die();
    // }
}
