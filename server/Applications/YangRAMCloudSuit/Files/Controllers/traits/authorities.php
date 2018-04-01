<?php
namespace Cloud\Files\Controllers\traits;

use Passport;
use PM\_CLOUD\FileMetaModel;

trait authorities {
    public function checkAdminAuthority(array $options = []){
        if(Passport::inGroup('Administrators', false)){
            return true;
        }
        return false;
    }

    public function checkReadAuthority(array $options = []){
        if(isset($options['files'])){
            return true;
        }
        new Status(404, true);
    }

	public function checkCreateAuthority(array $options = []){
        $CONFIG = $this->app->xProps['Config'];
        if($CONFIG['openUploads']){
            return true;
        }
        if(Passport::inGroup('Users', false)){
            return true;
        }
        return false;
    }

    public function checkUpdateAuthority(array $options = []){
        return $this->checkAdminAuthority($options);
    }

    public function checkDeleteAuthority(array $options = []){
        return $this->checkAdminAuthority($options);
    }
}