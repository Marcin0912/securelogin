<?php
class Validate {
    private $_passed = false,
            $_errors = array(),
            $_db     = null;

    public function __construct() {
        $this->_db = DB::getInstance();
    }

    public function check($source, $items = array()) {
        // $this->_passed = false;
        foreach($items as $item => $rules) {
            // print_r($rules);
            foreach($rules as $rule => $rule_value) {
                // echo "{$item} {$rule} must be {$rule_value}<br>";
                $value = trim($source[$item]);
                $item = escape($item);
                // echo $value;
                if($rule === 'required' && empty($value)) {
                    $this->addError("{$item} is required");
                } else if(!empty($value)) {
                    switch($rule) {

                        case 'min':
                            if(strlen($value) < $rule_value ) {
                                $this->addError("{$item} must be minimum of {$rule_value} characters in legth.");
                            }
                        break;

                        case 'max':
                            if(strlen($value) > $rule_value ) {
                                $this->addError("{$item} must be maximum of {$rule_value} characters in legth.");
                            }
                        break;

                        case 'matches':
                            if($value !== $source[$rule_value]) {
                                $this->addError("{$rule_value} must match the {$item}.");
                            }
                        break;

                        case 'unique':
                            $check = $this->_db->get($rule_value, array($item, '=', $value));
                            if($check->count()) {
                                $this->addError("that {$item} already exists.");
                            }
                        break;

                    }
                }
            }
        }
        if(empty($this->_errors)) {
            $this->_passed = true;
        }
        return $this;

    }

    private function addError($error) {
        $this->_errors[] = $error;
    }

    public function errors() {
        return $this->_errors;
    }

    public function passed() {
        return $this->_passed;
    }
}