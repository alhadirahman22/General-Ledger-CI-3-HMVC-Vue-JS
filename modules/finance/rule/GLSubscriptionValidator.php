<?php

namespace Modules\finance\rule;

use Illuminate\Support\Arr;
use \Respect\Validation\Validator as V;


use Modules\main\repository\MainRepository;
use \Respect\Validation\Exceptions\NestedValidationException;

// $array = ['products' => ['desk' => ['price' => 100]]];

// $price = Arr::get($array, 'products.desk.price'); // 100

class GLSubscriptionValidator
{
    /**
     * List of constraints
     *
     * @var array
     */
    protected $rules = [];
    protected $rules2 = [];


    /**
     * List of customized messages
     *
     * @var array
     */
    protected $messages = [];


    /**
     * List of returned errors in case of a failing assertion
     *
     * @var array
     */
    public $errors = [];


    /**
     * Just another constructor
     *
     * @return void
     */
    public function __construct()
    {
        $this->initMessages();
        $this->initRules();
        $this->initRules2();
    }

    /**
     * Set the user subscription constraints
     *
     * @return void
     */

    public function initRules()
    {
        $this->rules['fin_coa_id'] = V::number()->setTemplate($this->messages['number'])->notBlank()->setTemplate($this->messages['notBlank'])->setName('COA');
    }

    public function initRules2()
    {
        $this->rules2['fin_jurnal_voucher_id'] = V::number()->setTemplate($this->messages['number'])->notBlank()->setTemplate($this->messages['notBlank'])->setName('Jurnal Voucher');
        $this->rules2['fin_gl_no_bukti'] = V::notBlank()->setTemplate($this->messages['notBlank'])->setName('No Bukti');
        $this->rules2['fin_gl_date'] = V::notBlank()->setTemplate($this->messages['notBlank'])->date()->setTemplate($this->messages['date'])->setName('Trans Date');
        $this->rules2['selisih_total'] = V::intVal()->equals(0)->setName('Selisih');
    }



    public function initMessages()
    {
        $this->messages = [
            'notBlank'                 => '{{name}} is required.',
            'number'                 => '{{name}} must only contain Digit Number.',
            'creditdebit' => 'Your are not allowed to fill credit and debit',
            'ruleCoa' => 'At least 2 Coa for Jurnal',
            'creditdebit_0' => 'Credit and Debit cannot set zero for both',
            'balance' => 'Credit and Debit must be balance',
            'date' => '{{name}} unrecognized format'
        ];
    }

    /**
     * Assert validation rules.
     *
     * @param array $inputs
     *   The inputs to validate.
     * @return boolean
     *   True on success; otherwise, false.
     */
    public function assert(array  $inputs)
    {
        foreach ($this->rules as $rule => $validator) {
            for ($i = 0; $i < count($inputs); $i++) {

                try {
                    $x = $validator->assert(Arr::get($inputs[$i], $rule));
                } catch (NestedValidationException   $ex) {
                    $this->errors =  $ex->getMessages();

                    return false;
                }

                $debitCreditConfirmed = $this->debitCreditConfirmed($inputs[$i]);
                if (!$debitCreditConfirmed) {
                    return false;
                }
            }
        }
        $atLeastTwoCoa = $this->atLeastTwoCoa($inputs);
        if (!$atLeastTwoCoa) {
            return false;
        }

        return  $this->atBalance($inputs);
    }

    public function checkPay(array $inputs)
    {
        $fin_gl_no_bukti = $inputs['fin_gl_no_bukti']['code'];
        if (substr($fin_gl_no_bukti, 0, 1) == '#') {
            $main = new MainRepository();
            $code = $fin_gl_no_bukti;
            $availableReplace =  [
                [
                    'field' => 'status',
                    'value' => '1',
                ]

            ];
            $getData = $main->findByCode($code, $availableReplace);
            if ($getData) {
                $fieldTotalPrice = $getData['fieldTotalPrice'];
                $menuTab = $getData['menuTab'];
                $price = $getData[$fieldTotalPrice];
                $debit_total = $inputs['debit_total'];
                if ($price != $debit_total) {
                    $this->errors['notMatchPrice'] = 'The price should be exact match ' . number($price) . ' , you can check detail code ' . $code . ' on Navigasi ' . $menuTab;
                    return false;
                } else {
                    return true;
                }
            }
        }

        return true;
    }

    public function assert2(array  $inputs)
    {
        foreach ($this->rules2 as $rule => $validator) {
            try {
                $x = $validator->assert(Arr::get($inputs, $rule));
            } catch (NestedValidationException   $ex) {
                $this->errors =  $ex->getMessages();

                return false;
            }
        }
        $checkPay =  $this->checkPay($inputs);
        if (!$checkPay) {
            return false;
        }
        return true;
    }

    private function atBalance($inputs)
    {
        $collectionCredit = collect($inputs);

        $creditTotal = $collectionCredit->sum('credit');

        $collectionDebit = collect($inputs);

        $debitTotal = $collectionDebit->sum('debit');

        if ($debitTotal ==  $creditTotal) {
            return true;
        } else {
            $this->errors['balance'] = $this->messages['balance'];
            return false;
        }
    }

    private function atLeastTwoCoa($inputs)
    {
        $collection = collect($inputs);

        $counted = $collection->groupBy('fin_coa_id');
        $counted->all();

        if (count($counted) >= 2) {
            return true;
        } else {
            $this->errors['ruleCoa'] = $this->messages['ruleCoa'];
            return false;
        }
    }

    private function debitCreditConfirmed($inputs)
    {
        $exists = Arr::exists($inputs, 'credit');
        $exists2 = Arr::exists($inputs, 'debit');
        if ($exists && $exists2) {
            if ($inputs['credit'] > 0 && $inputs['debit'] > 0) {
                $this->errors['creditdebit'] = $this->messages['creditdebit'];
                return false;
            } else {
                if ($inputs['credit']  == $inputs['debit']) {
                    $this->errors['creditdebit'] = $this->messages['creditdebit_0'];
                    return false;
                }
                return true;
            }
        }
        return false;
    }

    public function errors()
    {
        return $this->errors;
    }

    public function extractErrors()
    {
        $collection = collect($this->errors);

        $flattened = $collection->flatten();

        $flattened = $flattened->all();

        $flattened = implode('<br/>', $flattened);
        return $flattened;
    }
}
