<?php

abstract class Base_Model {

    abstract public function get_data( $id );

    abstract public function store_data( $data );
    
}