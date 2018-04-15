<?php
namespace Cloud\Files\Controllers\traits;

use Passport;
use PM\_CLOUD\FileMetaModel;

trait authorities {
    public function checkAdminAuthority(array $options = []) : bool {
        if(Passport::inGroup('Administrators', false)){
            return true;
        }
        return false;
    }

    public function checkReadAuthority(array $options = []) : bool {
        if(isset($options['files'])){
            return true;
        }
        new Status(404, true);
    }

	public function checkCreateAuthority(array $options = []) : bool {
        $CONFIG = $this->app->xProps['Config'];
        if($CONFIG['openUploads']){
            return true;
        }
        if(Passport::inGroup('Users', false)){
            return true;
        }
        return false;
    }

    public function checkUpdateAuthority(array $options = []) : bool {
        return $this->checkAdminAuthority($options);
    }

    public function checkDeleteAuthority(array $options = []) : bool {
        return $this->checkAdminAuthority($options);
    }
}