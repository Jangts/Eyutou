<?php
namespace PM\_STUDIO;

use Status;
use Request;

abstract class BaseCRUDAVModel extends BaseListAVModel {
    use \AF\Controllers\traits\crudmethods;
    use \AF\Controllers\traits\administration;
    use traits\table;
    use traits\form;

    protected static function __viewWhere(){
        if(empty($_GET['tabid'])){
            return [];
        }else{
            return static::$__avmtabs[$_GET['tabid']]['where'];
        }
        
    }

    protected static function __createURL($basedir, $qs = ''){
        return $basedir.'/0/'.$qs;
    }

    protected static function __createTemplate($modelname){
        return $modelname::create();
    }

    /**
     * 检查权限
     * 
     * @access public
     * @param string(1) $type 权限类型，可选值为C/R/U/D/A
     * @param array $options 附加参数
     * @return bool
     */
    final public function checkAuthority($type, array $options = []){
        return $this->checkAdminAuthority();
    }
   
    final public function analysis($admininfo){
        $basedir = $this->request->ARI->dirname.'/'.$this->app->id.'/'.$this->request->ARI->patharr[1].'/'.$this->request->ARI->patharr[2];
        if(isset($this->request->ARI->patharr[3])){
            $guid = $this->request->ARI->patharr[3];
        }else{
            $guid = NULL;
        }
        switch($this->request->METHOD){
            case 'POST':
            if(empty($guid)){
                return $this->create();
            }
            case 'PUT':
            case 'PATCH':
            case 'UPDATE':
            return $this->update($guid);

            case 'DELETE':
            return $this->delete($guid);

            default:
            if(empty($guid)){
                if($guid==='0'){
                    return $this->readForm($basedir, $guid);
                }
                return $this->readList($basedir);
            }
            return $this->readForm($basedir, $guid);
        }
    }

    protected function readList($basedir){
        $require = static::__viewWhere();
        $orderby = static::__viewOrderBy();
        $range = static::__viewLimit();
        $modelname = static::$model or Status::cast('must specify a resource model.', 1422 );
        $modelname::__correctTablePrefix($this->app);
        $items = $modelname::query($require, $orderby, $range);
        $count = $modelname::getCOUNT($require);
        $qs = static::buildQueryString($range[2]);

        if(static::$creater){
            static::$creater['url'] =  static::__createURL($basedir, $qs);
        }
        $rows = $this->buildTableRows($basedir, $items, $qs);
        $this->assign('__avmtabs', 	self::buildTabs($basedir));
        $this->assign('__avmtags', '');
        $this->assign('itemlist', static::buildTable($rows));
        $this->assign('pagelist', self::buildPageList($count));
        $this->template = 'table.html';
        return $this;
    }

    protected function buildTableRows($basedir, $items = [], $qs = ''){
        $rows = [];
        foreach($items as $index=>$item){
            $itemurl = $basedir.'/'.$item[static::$pk];
            $row = [];
            foreach(static::$columns as $column){
                if(isset($item[$column['field_name']])){
                    $row[$column['field_name']] = [$item[$column['field_name']]];
                }else{
                    if($column['field_name']==='__index'){
                        $row['__index'] = [$index + 1];
                    }else{
                        $row[$column['field_name']] = ['-'];
                    }
                }
            }
            $row['__ops']   = ['<a href="'.$itemurl.$qs .'">编辑</a> | <a data-onclick="delete" data-submit-href="'.$itemurl.'" href="javascript:;">移除</a>'];
            
            $rows[] = $row;
        }
        return $rows;
    }

    public function readForm($basedir, $guid){
        $modelname = static::$model or Status::cast('must specify a resource model.', 1422);
        $modelname::__correctTablePrefix($this->app);
		if($guid==='0'){
            $item = static::__createTemplate($modelname);
		}else{
            $item = $modelname::byGUID($guid);

        }
        if(empty($item)){
            $this->assign('href', $basedir);
            $this->template = '404.html';
		    return $this;
        }
        $this->setSelections($item);
        $this->assign('form', self::buildForm($item->getArrayCopy()));
        if(isset($_GET['page'])){
            $qs = static::buildQueryString($_GET['page']);
        }else{
            $qs = static::buildQueryString();
		}
        $this->assignButtons($basedir, $item, $qs);

		$this->template = 'form.html';
		return $this;
    }

    abstract protected function setSelections($item);

    protected function assignButtons($basedir, $item, $qs){
        if($item[static::$pk]){
            if(method_exists(static::$model, 'recycle')||method_exists(static::$model, 'remove')){
                $name = '移除项目';
            }else{
                $name = '删除项目';
            }
			$button2 = [
				'name' 	=>	$name,
				'order'	=>	'delete',
				'form'	=>	'myform',
				'action'=>	$basedir.'/'.$item[static::$pk],
				'href'	=>	$basedir.$qs
			];
		}else{
			$button2 = NULL;
        }
        $buttons = [
			[
				'name' 	=>	'重置表单',
				'order'	=>	'reset',
				'form'	=>	'myform',
				'action'=>	'',
				'href'	=>	''
            ],
            [
				'name' 	=>	'返回列表',
				'order'	=>	'anchor',
				'form'	=>	'myform',
				'action'=>	'',
				'href'	=>	$basedir.$qs
			],
			$button2
        ];
        if(static::$sk){
            $buttons[] = [
				'name' 	=>	'保存到待审',
				'order'	=>	'submit',
				'form'	=>	'myform',
				'action'=>	$basedir.'/'.$item[static::$pk].'?state=0',
				'href'	=>	$basedir.$qs
			];
            $buttons[] = [
                'name' 	=>	'保存并生效',
                'order'	=>	'submit',
                'form'	=>	'myform',
                'action'=>	$basedir.'/'.$item[static::$pk].'?state=1',
                'href'	=>	$basedir.$qs
            ];
        }else{
            $buttons[] = [
                'name' 	=>	'提交保存',
                'order'	=>	'submit',
                'form'	=>	'myform',
                'action'=>	$basedir.'/'.$item[static::$pk],
                'href'	=>	$basedir.$qs
            ];
        }
        $this->assign('buttons', $buttons);
    }
}