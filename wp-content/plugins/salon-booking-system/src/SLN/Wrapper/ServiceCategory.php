<?php

class SLN_Wrapper_ServiceCategory {

    protected $object;

    public function __construct($object) {
        $this->object = $object;
    }

    public function getId() {
        return $this->object->term_id;
    }

    public function getName() {
        return $this->object->name;
    }

}
