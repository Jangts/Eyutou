<?php
namespace Pages\Ads\Models;

use Request;

class Admin_AdsViewModel extends \PM\_STUDIO\BaseCRUDViewModel {
	public static
	$model = 'Pages\Ads\Models\AdvertisementModel',
	$columns = [
		[
			'field_name'	=>	'__index',
			'display_name'	=>	'序号',
			'column_type'	=>	'',
			'classname'		=>	''	
		],
		[
			'field_name'	=>	'title',
			'display_name'	=>	'广告抬头',
			'column_type'	=>	'',
			'classname'		=>	''	
		],
		[
			'field_name'	=>	'type',
			'display_name'	=>	'广告类型',
			'column_type'	=>	'',
			'classname'		=>	''	
		],
		[
			'field_name'	=>	'__count',
			'display_name'	=>	'展现量',
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
			'field_name'	=>	'id',
			'display_name'	=>	'',
			'input_type'	=>	'hide'
		],
		[
			'field_name'	=>	'title',
			'display_name'	=>	'广告抬头',
			'input_type'	=>	'text'
		],
		[
			'field_name'	=>	'type',
			'display_name'	=>	'广告类型',
			'input_type'	=>	'select'
		],
		[
			'field_name'	=>	'content',
			'display_name'	=>	'广告内容',
			'input_type'	=>	'textarea'
		],
		[
			'field_name'	=>	'link',
			'display_name'	=>	'广告链接',
			'input_type'	=>	'text'
		]
	],
	$types = [
		'html'	=>	'自定HTML内容',
		'image'	=>	'图片链接',
		'text'	=>	'文字链接',
		'video'	=>	'视频广告'
	],
	$creater = [
		'name'	=>	'创建广告'
	];

	public function initVars(){
		return [
			'listname'	=>	'广告列表',
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
            $itemurl = $basedir.'/'.$item->id;
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