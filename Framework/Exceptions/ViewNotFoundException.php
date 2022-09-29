<?php

class ViewNotFoundException extends Exception {
    public function __construct($message = "View not found") {
        parent::__construct($message, "0003");
    }
}