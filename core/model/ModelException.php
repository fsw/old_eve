<?php

class ModelException extends Exception
{

    public function __construct($message, $fields) {

    	parent::__construct($message);
    }

    public function __toString() {
        return __CLASS__ . ": {$this->message}\n";
    }

}
