<?php
namespace PM\_CLOUD\traits;

use PM\_CLOUD\TableMetaModel;
use PM\_CLOUD\TableRowMetaModel;

trait trmm_checking {
    /**
     * 检查更新信息
     */
    private static function checkPutData($input){
        $input = self::checkStatus($input);
        $form = array_map('htmlspecialchars', $input);
        // 英文只用作修改，所以不用补全
        $intersect = array_intersect_key($form, self::$defaultPorpertyValues);
        $intersect["SK_MTIME"] = DATETIME;
		$desc = trim(preg_replace('/\s+/', ' ', preg_replace('/[\n\r\t]+/', '', $intersect["DESCRIPTION"])));
		if(empty($desc)){
			unset($intersect["DESCRIPTION"]);
		}else{
			$intersect["DESCRIPTION"] = $desc;
		}
        return self::__checkValues($intersect);
    }

    /**
     * 检查状态
     */
    public static function checkStatus($input){
        // var_dump($input["SK_STATE"], $input["SK_STATE"]==1);
        // exit;
        if($input["SK_STATE"]==1){
            if(empty($input["PUBTIME"])){
                $input["PUBTIME"] = DATETIME;
            }
            $input["SK_STATE"] = '1';
        }else{
            unset($input["PUBTIME"]);
            $input["SK_STATE"] = '0';
        }
        return $input;
    }
}

trait trm_checking {
    private static function checkPostData($input){
        $input = TableRowMetaModel::checkStatus($input);

        if($tablemeta = TableMetaModel::byGUID($input['TABLENAME'])){
            if($props = self::getFullDefaultPropertyValues($input['TABLENAME'], $tablemeta)){
                $defaultPorpertyValues = $props[0];
                $extendedPropertyValues = $props[1];
                $attrRestraint = $props[2];

                $form = array_map(function($val){
                    return htmlspecialchars($val, ENT_COMPAT, 'UTF-8');
                }, $input);

                $meta = array_intersect_key($form, $defaultPorpertyValues);
                $xtnd = array_intersect_key($form, $extendedPropertyValues);
                if(array_key_exists('X_ATTRS', $extendedPropertyValues)){
                    if($attrRestraint){
                        $defaultValues = $attrRestraint->getDefaultValues();
                        $attributes = array_merge($defaultValues, $attrRestraint->correntValues($form, true));
                        $xtnd['X_ATTRS'] = json_encode($attributes);                   
                    }else{
                        $xtnd['X_ATTRS'] = '';
                    }
                }
                $meta["SK_MTIME"] = DATETIME;
                $meta = array_merge($defaultPorpertyValues, $meta);
                $xtnd = array_merge($extendedPropertyValues, $xtnd);
                $meta["TABLENAME"] = $input['TABLENAME'];
                $meta["SK_CTIME"] = DATETIME;
                return self::checkSEO(TableRowMetaModel::__checkValues($meta, true), $xtnd);
            }
        }
        new Status(1443, '', 'Unknow Tablename', true);       
    }

    private static function checkPutData($input, $savedProperties){
        // $input = TableRowMetaModel::checkStatus($input);

        $props = self::getFullDefaultPropertyValues($input['TABLENAME']);
        $defaultPorpertyValues = $props[0];
        $extendedPropertyValues = $props[1];
        $attrRestraint = $props[2];

        $form = array_map(function($val){
            return htmlspecialchars($val, ENT_COMPAT, 'UTF-8');
        }, $input);

        $meta = array_intersect_key($form, $defaultPorpertyValues);
        $xtnd = array_intersect_key($form, $extendedPropertyValues);
        if(array_key_exists('X_ATTRS', $extendedPropertyValues)){
            if(isset($form['__attributes'])){
                if($attrRestraint&&is_array($xtnd['__attributes'])){
                    $xtnd['X_ATTRS'] = json_encode($form['__attributes']);      
                }else{
                    $xtnd['X_ATTRS'] = '';
                }
                unset($xtnd['__attributes']);
            }else{
                $xtnd['X_ATTRS'] = '';
            }
        }
        $meta["SK_MTIME"] = DATETIME;
        $meta["ID"] = $savedProperties["ID"];
        $meta["TABLENAME"] = $savedProperties['TABLENAME'];
        $meta["SK_CTIME"] = $savedProperties['SK_CTIME'];
        
        return self::checkSEO(TableRowMetaModel::__checkValues($meta), $xtnd);
    }

    private static function checkSEO($meta, $xtnd){
        switch($meta["TYPE"]){
            case 'article':
            case 'note':
            case 'news':
            $preparation = isset($xtnd["CONTENT"]) ? $xtnd["CONTENT"] : '';
            break;

            case 'bill':
            $preparation = isset($xtnd["REMARK"]) ? $xtnd["REMARK"] : '';
            break;

            case 'job':
            $preparation = isset($xtnd["POSI_DESC"]) ? $xtnd["POSI_DESC"] : '';
            break;

            case 'resume':
            $preparation = isset($xtnd["RESUME"]) ? $xtnd["RESUME"] : '';
            break;
            
            case 'wiki':
            $preparation = isset($xtnd["MEANING"]) ? $xtnd["MEANING"] : '';
            break;

            default:
            $preparation = isset($meta["TITLE"]) ? $meta["TITLE"] : '';
        }
        $desc = trim(preg_replace('/\s+/', ' ', preg_replace('/[\n\r\t]+/', '', strip_tags(htmlspecialchars_decode($meta["DESCRIPTION"])))));
        $meta["DESCRIPTION"] = $desc!='' ? $desc : trim(preg_replace('/\s+/', ' ', preg_replace('/[\n\r\t]+/', '', strip_tags(htmlspecialchars_decode($preparation)))));
        $meta["DESCRIPTION"] = mb_substr($meta["DESCRIPTION"], 0, 128, "utf-8");  

        return [$meta, $xtnd];
    }
}