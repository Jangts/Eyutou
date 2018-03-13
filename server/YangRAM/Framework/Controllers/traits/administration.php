<?php
namespace AF\Controllers\traits;

use Status;
use Response;
use Passport;

trait administration {
    public function checkCreateAuthority(array $options = []){
        return $this->checkUpdateAuthority($options);
    }

    public function checkReadAuthority(array $options = []){
        return $this->checkAdminAuthority($options);
    }

    public function checkUpdateAuthority(array $options = []){
        return $this->checkAdminAuthority($options);
    }

    public function checkDeleteAuthority(array $options = []){
        return $this->checkAdminAuthority($options);
    }
}