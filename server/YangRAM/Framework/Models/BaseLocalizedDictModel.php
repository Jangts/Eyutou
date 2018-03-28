<?php
namespace Lib\models;

/**
 * @class Lib\models\LocalizedDictModel
 */
use Status;
use Tangram\MODEL\ObjectModel;

final class LocalizedDictModel implements \DataModel {
    use \Tangram\MODEL\traits\magic;
    use \Tangram\MODEL\traits\arraylike;

	private static function pickLang($filename, $pattern){
		global $NEWIDEA;
		$cxt = explode('{{lang}}', $pattern);
		$len = strlen($filename) - strlen(implode('', $cxt));
		$lan = substr($filename, strlen($cxt[0]), $len);
		$NEWIDEA->LANGUAGE = $lan;
		if(empty($lan)){
			return false;
		}
		return [$lan, $filename];
	}

	public static function checkLang($pattern, $is_dir = false, $lang = false){
		global $NEWIDEA;
		if(empty($lang)||!is_string($lang)) {
			if(empty($NEWIDEA->LANGUAGE)||!is_string($NEWIDEA->LANGUAGE)){
				$lang = $NEWIDEA->LANGUAGE = _LANG_;
        	}else{
				$lang = $NEWIDEA->LANGUAGE;
			}
		}
		if($is_dir){
			$dir = str_replace('{{lang}}', $lang, $pattern);
			if(is_dir($dir)){
				$NEWIDEA->LANGUAGE = $lang;
				return [$lang, $dir];
			}		
		}else{
			$filename = str_replace('{{lang}}', $lang, $pattern);
			if(is_file($filename)){
				$NEWIDEA->LANGUAGE = $lang;
				return [$lang, $filename];
			}
		}
		$la = substr($lang, 0, 2);
		$files = glob(str_replace('{{lang}}', $la.'-*', $pattern));
		
		if(isset($files[0])){
			return self::pickLang($files[0], $pattern);
		}
		if($lang!==$NEWIDEA->LANGUAGE){
			return self::checkLang($pattern, $is_dir, $NEWIDEA->LANGUAGE);
		}
		return false;
	}

	protected
	$code;

	public function __construct($type = 'phparr'){
		$filename = $this->getFilename($type);
		$this->__put($filename, $type);
	}
	
	protected function __put($filename, $type = 'phparray'){
		switch($type){
			case 'json':
			$this->modelProperties = json_decode(file_get_contents($filename), true);
			break;

			case 'xml':
			$this->modelProperties = self::getArrayByXml(file_get_contents($filename), $root = 'dict');
			break;

			case 'ini':
			$content = file_get_contents($filename);
			$rows = explode(PHP_EOF, $content);
			foreach($rows as $row){
				$row = trim($rows);
				$propArr = preg_split('/\s+/', $row);
				if(count($propArr)===2){
					$this->modelProperties[$propArr[0]] = $propArr[1];
				}elseif(count($propArr)===3){
					if(isset($this->modelProperties[$propArr[0]])&&is_array($this->modelProperties[$propArr[0]])){
						$this->modelProperties[$propArr[0]][$propArr[1]] = $propArr[2];
					}else{
						$this->modelProperties[$propArr[0]] = [
							$propArr[1] => $propArr[2]
						];
					}
				}
			}
			break;

			case 'phpini':
			$this->modelProperties = parse_ini_file($filename);
			break;

			default:
			$this->modelProperties = include $filename;
		}
	}

	protected function checkFilename($filename, $type = 'phparray'){
		switch($type){
			case 'json':
			if(is_file($filename.'.json')){
				return $filename.'.json';
			}
			return false;

			case 'xml':
			if(is_file($filename.'.xml')){
				return $filename.'.xml';
			}
			return false;

			case 'ini':
			case 'phpini':
			if(is_file($filename.'.ini')){
				return $filename.'.ini';
			}
			return false;

			default:
			if(is_file($filename.'.php')){
				return $filename.'.php';
			}
			return false;
		}
	}

	final public function get($index){
        if(isset($this->modelProperties[$index])){
            return $this->modelProperties[$index];
		}
		if($index==='code'){
			return $this->code;
		}
        return $index;
    }
	
	protected function getFilename($type){
		global $NEWIDEA;
		$extn = '.ini';
		switch($type){
			case 'ini':
			case 'json':
			case 'xml':
			break;

			case 'phpini':
			$extn = '.ini';
			break;
	
			default:
			$extn = '.php';
		}
		$lang = strtolower($NEWIDEA->LANGUAGE);
		$lang_check_result = self::checkLang(AP_CURR.'Locales/{{lang}}'.$extn, false, $lang);
		if($lang_check_result){
			$this->code = $NEWIDEA->LANGUAGE = $lang_check_result[0];
			return $lang_check_result[1];
		}
		$lang_check_result = self::checkLang(LPATH.'{{lang}}', true, $lang);
		if($lang_check_result){
			$this->code = $lang_check_result[0];
			if(is_file($lang_check_result[1].'/'.strtolower(AI_CURR).$ext)){
				$this->code = $NEWIDEA->LANGUAGE = $lang_check_result[0];
				return $lang_check_result[1].'/'.strtolower(AI_CURR).$extn;
			}
			if(is_file($lang_check_result[1].'/common'.$ext)){
				$this->code = $NEWIDEA->LANGUAGE = $lang_check_result[0];
				return $lang_check_result[1].'/common'.$extn;
			}
		}
		new Status(1444, 'No matching ['.$type.']['.$lang.'] dictionary file of this application ['.AI_CURR.']', true);
	}

	final public function has($index){
        if(isset($this->modelProperties[$index])){
            return true;
        }
        return false;
    }

    final public function set($index, $el){
        return false;
    }

    final public function uns($index){
        return false;
	}
	
	final public function str(){
        return $this->json_encode();
    }
}
