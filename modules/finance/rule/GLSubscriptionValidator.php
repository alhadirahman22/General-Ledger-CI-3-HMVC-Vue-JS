<?php

namespace Modules\finance\rule;

use \Respect\Validation\Validator as V;
use \Respect\Validation\Exceptions\NestedValidationException;


use Illuminate\Support\Arr;

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
        $this->initRules();
        $this->initMessages();
    }

    /**
     * Set the user subscription constraints
     *
     * @return void
     */

    public function initRules()
    {
        $this->rules['fin_coa_id'] = V::notEmpty()->setName('fin_coa_id');
        $this->rules['fin_gl_id'] = V::notEmpty()->setName('fin_gl_id');
        $this->rules['fin_coa_id'] = V::notEmpty()->setName('fin_coa_id');
        $this->rules['fin_gl_referensi'] = V::notEmpty()->setName('fin_gl_referensi');
        $this->rules['debit'] = V::notEmpty()->setName('debit');
        $this->rules['credit'] = V::notEmpty()->setName('credit');
        $this->rules['desc'] = V::notEmpty()->setName('desc');
        $this->rules['ref'] = V::notEmpty()->setName('ref');
    }

    public function initMessages()
    {
        $this->messages = [
            'notEmpty'                 => '{{name}} is required.',
            'number'                 => '{{name}} must only contain Digit Number.',

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
        // print_r($inputs);
        // die();
        foreach ($this->rules as $rule => $validator) {
            for ($i = 0; $i < count($inputs); $i++) {

                try {
                    $x = $validator->assert(Arr::get($inputs[$i], $rule));
                } catch (NestedValidationException   $ex) {
                    // $this->errors =  $ex->getMessages($this->messages);
                    $errors = $ex->findMessages([
                        'notEmpty'                 => '{{name}} is required.',
                        'number'                 => '{{name}} must only contain Digit Number.',

                    ]);
                    return false;
                }
            }
        }

        return true;
    }

    public function errors()
    {
        return $this->errors;
    }
}
