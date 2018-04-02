<?php
namespace PM\_STUDIO;

use Status;
use Request;

abstract class BaseTrashCanAVModel extends BaseTableAVModel {
    public static
    $model,
	$columns = [
		[
			'field_name'	=>	'__index',
			'display_name'	=>	'序号',
			'column_type'	=>	'',
			'classname'		=>	''	
		],
		[
			'field_name'	=>	'name',
			'display_name'	=>	'项目名称',
			'column_type'	=>	'',
			'classname'		=>	''	
		],
		[
			'field_name'	=>	'mtime',
			'display_name'	=>	'回收时间',
			'column_type'	=>	'',
			'classname'		=>	''	
		],
		[
			'field_name'	=>	'__ops',
			'display_name'	=>	'操作',
			'column_type'	=>	'',
			'classname'		=>	''	
		]
    ];
    
    protected static function __viewWhere(){
        return [
			'SK_IS_RECYCLED'	=>	1
		];
    }

    final public function analysis($admininfo){
        // 根据当前分页信息获取选取区间
        $range = self::__viewLimit();
        // 计算相应的列表页地址
		$stagedir = $this->request->ARI->dirname.'/'.$this->app->id.'/'.$this->request->ARI->patharr[1].'/'.$this->request->ARI->patharr[2].'/';
        $listurl = $stagedir.'?page='.$range[2];

        // 检查是否需要操作
        if(isset($this->request->ARI->patharr[3])){
            return $this->execDeletion($listurl);
        }

        // 生成列表并响应
		return $this->readList($stagedir, $range);
    }

    protected function readList($stagedir, $range){
        $modelname = static::$model or Status::cast('must specify a resource model.', 1422 );
        $modelname::__correctTablePrefix($this->app);
        // 获取回收项目列表的筛选条件
        $require = static::__viewWhere();
        // 查询总量
        $count = $modelname::getCOUNT($require);
        //选取指定行，并转换格式
        $items = $modelname::query($require, static::$__sortby, $range);
        $rows = $this->buildTableRows($stagedir, $items, $range);

        $this->assign('__avmtabs', '');
        $this->assign('__avmtags', '');
        $this->assign('itemlist', static::buildTable($rows));
        $this->assign('pagelist', self::buildPageList($count));
        $this->template = 'table.html';
        return $this;
    }

    abstract protected function buildTableRows($stagedir, $items = [], array $range = [0, 0, 1], $sort = '');

    protected function execDeletion($listurl){
        $modelname = static::$model or Status::cast('must specify a resource model.', 1422 );
        $modelname::__correctTablePrefix($this->app);
        if(is_numeric($this->request->ARI->patharr[3])&&$this->request->ARI->patharr[3]>0){
            if($item = $modelname::byGUID($this->request->ARI->patharr[3])){
                if(isset($this->request->ARI->patharr[4])&&$this->request->ARI->patharr[4]==='delete'){
                    $this->delete($item);
                }else{
                    $this->recover($item);
                }
            }
        }
        $this->assign('href', $listurl);
        $this->template = 'recycle.html';
        return $this;
    }
    

    protected function recover($item){
        $item->recycle(0);
    }

    protected function delete($item){
        $item->destroy();
    }
}