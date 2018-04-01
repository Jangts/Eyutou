<?php
namespace Eyutou\Spider\Models;

use Status;
use Request;
use Response;
use Tangram\MODEL\ObjectModel;
use App;

class ProjectsAVModel extends \PM\_STUDIO\BaseTableAVModel {
    public static
	$columns = [
		[
			'field_name'	=>	'__index',
			'display_name'	=>	'序号',
			'column_type'	=>	'',
			'classname'		=>	''	
		],
		[
			'field_name'	=>	'title',
			'sorting_name'	=>	'title_gb',
			'display_name'	=>	'新闻标题',
			'length'		=>	30,
			'column_type'	=>	'',
			'classname'		=>	''	
		],
		[
			'field_name'	=>	'crttime',
			'sorting_name'	=>	'ctime',
			'display_name'	=>	'创建时间',
			'column_type'	=>	'',
			'classname'		=>	''	
		],
		[
			'field_name'	=>	'modtime',
			'sorting_name'	=>	'mtime',
			'display_name'	=>	'修改时间',
			'column_type'	=>	'',
			'classname'		=>	''	
		],
		[
			'field_name'	=>	'pubtime',
			'sorting_name'	=>	'ptime',
			'display_name'	=>	'发布时间',
			'column_type'	=>	'',
			'classname'		=>	''	
		],
		[
			'field_name'	=>	'__count',
			'display_name'	=>	'点击量',
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
	$creater = [
		'name'	=>	'创建新资讯'
	],
	$__sorts = [
		'id'				=>	TableRowMetaModel::ID_ASC,
		'id_reverse'		=>	TableRowMetaModel::ID_DESC,
		'ctime'				=>	TableRowMetaModel::CTIME_ASC,
		'ctime_reverse'		=>	TableRowMetaModel::CTIME_DESC,
		'mtime'				=>	TableRowMetaModel::MTIME_ASC,
		'mtime_reverse'		=>	TableRowMetaModel::MTIME_DESC,
		'ptime'				=>	TableRowMetaModel::PUBTIME_ASC,
		'ptime_reverse'		=>	TableRowMetaModel::PUBTIME_DESC,
		'level'				=>	TableRowMetaModel::LEVEL_ASC,
		'level_reverse'		=>	TableRowMetaModel::LEVEL_DESC,
		'title'				=>	TableRowMetaModel::TITLE_ASC,
		'title_reverse'		=>	TableRowMetaModel::TITLE_DESC,
		'title_gb'			=>	TableRowMetaModel::TITLE_ASC_GBK,
		'title_gb_reverse'	=>	TableRowMetaModel::TITLE_DESC_GBK
	],
	$__sortby = TableRowMetaModel::PUBTIME_DESC;

    public function initialize(){
        if(!static::$staticVasInited){
            // static::$classtabs = [
            //     'jtxw'	=>	[
            //         'name'	=>	'集团新闻',
            //         'title'	=>	'集团新闻',
            //         'where'	=>	[
            //             'FOLDER'	=>	7
            //         ]
            //     ],
            //     'hydt'	=>	[
            //         'name'	=>	'行业动态',
            //         'title'	=>	'行业动态',
            //         'where'	=>	[
            //             'FOLDER'	=>	8
            //         ]
            //     ]
            // ];
        }
		return [
			'listname'	=>	'新闻列表',
			'itemlist'	=>	'<table class="table-view"><tr><td></td></tr></table>',
			'pagelist'	=>	'<ul><li>1</li></ul>'
		];
    }

	public function analysis($admininfo){
		$range = self::__viewLimit();
		$orderby = self::__viewOrderBy();
		if(empty($_GET['tabalias'])||empty(static::$classtabs[$_GET['tabalias']])){
            $folder = NULL;
        }else{
			$folder = static::$classtabs[$_GET['tabalias']]['where']['FOLDER'];
        }
		$count = TableRowMetaModel::getCOUNT('news', $folder, TableRowMetaModel::UNRECYCLED);
		$list  = TableRowMetaModel::getRows('news', $folder, TableRowMetaModel::UNRECYCLED, $orderby, $range[0], $range[1]);

		$rows = [];
		$stagedir = $this->request->ARI->dirname.'/'.$this->app->ID;
		$basedir = $stagedir.'/news/';
		if(isset($_GET['sort'])){
            $sort = $_GET['sort'];
        }else{
            $sort = '';
        }
		foreach($list as $index=>$news){
			$itemurl = $basedir.$news->ID;
			$rows[] = [
				'__index'	=>	[$index + 1],
				'title'		=>	[$news->TITLE, $itemurl.'?page='. $range[2] .'&sort'. $sort, false],
				'crttime'	=>	[$news->SK_CTIME],
				'modtime'	=>	[$news->SK_MTIME],
				'pubtime'	=>	[$news->PUBTIME],
				'__count'	=>	[0],
				'__ops'		=>	['<a href="'.$itemurl.'?page='. $range[2] .'&sort'. $sort .'">编辑</a> | <a data-onclick="delete" data-submit-href="/applications/cloudtables/rows/'.$news->ID.'" href="javascript:;">移除</a>']
			];
		}

		if(empty($_GET['tabalias'])){
            self::$creater['url'] = $basedir;
        }else{
            self::$creater['url'] = $basedir.'?tabalias='.$_GET['tabalias'];
        }
		

		$this->assign('classtabs', 	self::buildTabs($stagedir.'/news-list/'));
		$this->assign('itemlist', 	self::buildTable($rows, $range[2]));
		$this->assign('pagelist', 	self::buildPageList($count));
		
		$this->template = 'table.html';
		return $this;
	}
}