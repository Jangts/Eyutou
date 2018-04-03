<?php
namespace PM\_CLOUD;

use Status;
use Request;

abstract class AbstractTableRowCRUDAVModel extends \PM\_STUDIO\BaseFormAVModel {
	public static
	$tablename = NULL,
	$formname = 'Edit Row Info',
	$itemname = '',
	$listurl = '';

	public static function __putDataToNewModel($item){
		if(isset($_GET['tabid'])&&is_array(self::$__avmtabs[$_GET['tabid']])){
			foreach(self::$__avmtabs[$_GET['tabid']]['where'] as $prop=>$value){
				$item->__set($prop, $value);
			}
		}
		return $item;
	}

	public static function loadGroupTabs(){
		if($groups = TRGroupModel::getGroupsByTableName(static::$tablename)){
			$tabs = [];
			foreach ($groups as $group) {
				$tabs['group'.$group->id] = [
					'name'	=>	$group->name,
					'title'	=>	empty($group->description) ? $group->name : $group->description,
					'where'	=>	['GROUPID'=>$group->id]
				];
			}
			static::$__avmtabs = $tabs;
		}
	}

	public static function loadStaticProperties(){
		if(
			($table = TableMetaModel::byGUID(static::$tablename))
			&&is_file($filename = __DIR__.'/default_avmvar_providers/'.$table->type.'_form.json')
			&&($vars = json_decode(file_get_contents($filename), true))
		){
			self::setStaticProterties($vars);
		}
	}

	public function initialize(){
		if(empty(static::$tablename)){
			// new Status(1414, '', 'must have static property "tablename".');
			static::$tablename = $this->request->ARI->patharr[1];
		}
		static::loadGroupTabs();
		static::loadStaticProperties();
		if(empty(static::$itemname)){
			$table = TableMetaModel::byGUID(static::$tablename);
			static::$itemname = $table->item;
		}
		if(empty(static::$listurl)){
			static::$listurl = '/'.$this->request->ARI->patharr[1].'/'.static::$tablename.'/';
		}
		return [
			'formname'	=>	static::$formname
		];
	}

	public function analysis($admininfo){
		$basedir = $this->request->ARI->dirname.'/'.$this->app->id.static::$listurl;
		if(isset($this->request->ARI->patharr[3])&&is_numeric($this->request->ARI->patharr[3])&&$this->request->ARI->patharr[3]>0){
			$guid = $this->request->ARI->patharr[3];
			$news = TableRowModel::byGUID($guid);
			
			if(!$news){
				$this->assign('href', $basedir);
				$this->assign('msg', '资源不存在，将返回列表页');
				$this->template = '404.html';
				return $this;
			}
			$method = 'PUT';
			$button2 = [
				'name' 	=>	'移除'.static::$itemname,
				'order'	=>	'delete',
				'form'	=>	'myform',
				'action'=>	__aurl__.'cloudtables/rows/'.$guid,
				'href'	=>	$basedir
			];
		}else{
			$guid = 0;
			$news = TableRowModel::create(['tablename' => static::$tablename]);
			$news = static::__putDataToNewModel($news);
			$method = 'POST';
			$button2 = NULL;
		}

		$groups = TRGroupModel::getGroupsByTableName(static::$tablename);
		$groupOptions = [];
		foreach($groups as $group){
			$groupOptions[] = [$group['id'], $group['name']];
		}
		static::$selectOptions['GROUPID'] = $groupOptions;

		$this->assign('form', self::buildForm($news->getArrayCopy(), $method));

        if(isset($_GET['page'])){
            $qs = static::buildQueryString($_GET['page']);
        }else{
            $qs = static::buildQueryString();
		}
		
		$this->assign('buttons', [
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
			$button2,
			[
				'name' 	=>	'保存到待审',
				'order'	=>	'submit',
				'form'	=>	'myform',
				'action'=>	__aurl__.'cloudtables/rows/'.$guid.'?state=0',
				'href'	=>	$basedir.$qs
			],
			[
				'name' 	=>	'发布'.static::$itemname,
				'order'	=>	'submit',
				'form'	=>	'myform',
				'action'=>	__aurl__.'cloudtables/rows/'.$guid.'?state=1',
				'href'	=>	$basedir.$qs
			]
		]);
		
		$this->template = 'form.html';
		return $this;
	}
}