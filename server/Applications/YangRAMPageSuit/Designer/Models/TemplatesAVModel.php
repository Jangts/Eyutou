<?php
namespace Pages\Designer\Models;

use Request;

class TemplatesAVModel extends \PM\_STUDIO\BaseCRUDAVModel {
	public static
	$model = 'Pages\Ads\Models\AdvertisementModel',
	$sk = null,
	$columns = [
		[
			'field_name'	=>	'__index',
			'display_name'	=>	'序号',
			'column_type'	=>	'',
			'classname'		=>	''	
		],
		[
			'field_name'	=>	'filename',
			'display_name'	=>	'模板文件',
			'column_type'	=>	'',
			'classname'		=>	''	
		],
		[
			'field_name'	=>	'__ops',
			'display_name'	=>	'操作',
			'column_type'	=>	'',
			'classname'		=>	''	
		]
	],
	$inputs = [
		[
			'field_name'	=>	'filename',
			'display_name'	=>	'文件名',
			'input_type'	=>	'text',
			'readonly'		=>	true
		],
		[
			'field_name'	=>	'content',
			'display_name'	=>	'广告内容',
			'input_type'	=>	'textarea'
		]
	];

	public function initialize(){
		return [
			'listname'	=>	'模板列表',
			'itemlist'	=>	'<table class="table-view"><tr><td></td></tr></table>',
			'pagelist'	=>	'',
			'formname'	=>	'编辑广告信息'
		];
	}

	protected function buildTableRows($basedir, $items = [], array $range = [0, 0, 1], $sort = ''){
        $rows = [];
        foreach($items as $index=>$item){
            if(isset(self::$types[$item->type])){
                $type = [self::$types[$item->type]];
            }else{
                $type = ['图片链接'];
            }
            $itemurl = $basedir.'-'.$item->type.'/'.$item->id;
            $rows[] = [
                '__index'	=>	[$index + 1],
                'title'		=>	[$item->title, $itemurl.'?page='. $range[2] .'&sort'. $sort, false],
                'type'		=>	$type,
                '__count'	=>	[0],
                '__ops'		=>	['<a href="'.$itemurl.'?page='. $range[2] .'&sort'. $sort .'">编辑</a> | <a data-onclick="delete" data-submit-href="'.$itemurl.'" href="javascript:;">移除</a>']
            ];
        }
        return $rows;
	}
	
	protected function setSelections($item){
		$types = [];
		foreach(self::$types as $val=>$text){
			$types[] = [$val, $text];
		}
		static::$selectOptions['type'] = $types;
    }
}