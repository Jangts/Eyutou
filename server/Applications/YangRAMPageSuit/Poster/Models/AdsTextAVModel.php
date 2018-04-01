<?php
namespace Pages\Ads\Models;

use Request;

class AdsTextAVModel extends AdsTypeAVModel {
	public static
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
			'input_type'	=>	'text'
		],
		[
			'field_name'	=>	'link',
			'display_name'	=>	'广告链接',
			'input_type'	=>	'text'
		]
	],
	$selectOptions = [
		'type'	=>	[['text',	'文字链接']]
	];
}