<?php
// 核心数据模块共用的命名空间，命名空间Tangram的子空间
namespace Tangram\MODEL;

// 引入相关命名空间，以简化书写
use Tangram\IDEA;
use Status;
use Tangram\MODEL\ObjectModel;

/**
 * @class Tangram\MODEL\ApplicationExtendedPropertiesModel
 * Application Configuration Options Model
 * 应用配置选项模型
 * 
 * @var string 		$Suitspace					套件的命名空间，如果有的话
 * @var string		$Namespace					应用的命名空间
 * @var string		$Name						应用名称
 * @var string 		$Version					应用版本号，内建应用与核心模块同号，即IDEA::VERSION
 * @var string		$Homepage					应用主页
 * @var string		$Issues						应用的讨论组
 * @var array		$AuthorInfs					作者信息
 * @prop string		$AuthorInfs->AuthorID		作者标识
 * @prop string		$AuthorInfs->AuthorName		作者名称
 * @prop string		$AuthorInfs->Developers		开发者名单
 * @prop string		$AuthorInfs->Homepage		作者主页
 * @var array 		$Routers					自定义路由列表
 * @var array 		$Requires					应用依赖（标准库）
 * 
 * @final
 * @author      Jangts
 * @version     5.0.0
**/
final class ApplicationExtendedPropertiesModel extends ObjectModel {
	private
	$appinfoid = -1,
	$installPath = '',
	$optionFilename = '';

	protected $modelProperties = [
		'AuthorInfs'	=> [
			'AuthorID'	=>	'',
			'AuthorName'	=>	'',
			'Developers'	=>	'',
			'Homepage'		=>	''
		],
		'Config'		=>	[],
		'Description'	=>	'',
		'Homepage'		=>	'',
		'Issues'		=>	'',
		'KeyWords'		=>	'',
		'Name'			=>	'',
		'Namespace'		=>	'',
		'Permissions'	=>	[],
		'Requires'		=>	[],
		'Routers'		=>	[],
		'SCode'			=>	'',
		'Suitspace'		=>	'',		
		'Version'		=>	IDEA::VERSION
	];

	
	public function __construct(array $appinfo){
		if(isset($appinfo['APPID'])){
			$this->appid = $appinfo['APPID'];
			$this->installPath = __ROOT__.$appinfo['DIR'];
			$this->optionFilename = __ROOT__.$appinfo['DIR'].'appprops.json';
			if($realpath = realpath($this->optionFilename)){
				$this->loadProperties($appinfo);
			}else{
				new Status(1422.0, 'Application '.$appinfo['Name'].' Initialization Failure', 'Cannot Find Options File [' . $this->optionFilename . ']', true, true);
			}
		}else{
			new Status(1415.1, 'Parameter Error!', true);
		}
	}

	private function loadProperties(array $appinfo){
		$this->modelProperties['SCode'] = $appinfo['SCode'];
		$string = file_get_contents($this->optionFilename);
		$props = json_decode($string, true);
		if($props){
			$this->modelProperties['Name'] = $appinfo['Name'];
			empty($props['name']) or $this->modelProperties['Name'] = $props['name'];
			if(empty($props['namespace'])){
				new Status(1400.0, 'Application '.$appinfo['Name'].' Initialization Failure', 'Must define A Namespace For Application.', true);
			}
			if(isset($props['suitspace'])){
				$this->modelProperties['Suitspace'] = $props['suitspace'];
				$this->modelProperties['Namespace'] = $props['suitspace'].'\\'.$props['namespace'];
			}else{
				$this->modelProperties['Namespace'] = $props['namespace'];
			}
			if(isset($props['metadata'])){
				$this->loadMetaData($props['metadata']);
			}
			if(isset($props['metadata'])){
				$this->loadAuthorInfo($props['metadata']);
			}
			if(isset($props['routers'])&&is_array($props['routers'])){
				$this->modelProperties['Routers'] = $props['routers'];
			}
			if(isset($props['requires'])){
				$this->loadRequires($props['requires']);
			}
			if(isset($props['config'])&&is_array($props['config'])){
				$this->modelProperties['Config'] = $props['config'];
			}
		}else{
			new Status(1400.0, 'Application '.$appinfo['Name'].' Initialization Failure', 'Please Check You Options.json File.', true);
		}
	}

	private function loadMetaData(array $metadata){
		empty($metadata['version']) or $this->modelProperties['Version'] = $metadata['version'];
        empty($metadata['homepage']) or $this->modelProperties['Homepage'] = $metadata['homepage'];
        empty($metadata['issues']) or $this->modelProperties['Issues'] = $metadata['issues'];
	}

	private function loadAuthorInfo(array $authorinfs){
		empty($authorinfs['author_id']) or $this->modelProperties['AuthorInfs']['AuthorID'] = $authorinfs['author_id'];
		empty($authorinfs['author_name']) or $this->modelProperties['AuthorInfs']['AuthorName'] = $authorinfs['author_name'];
		empty($authorinfs['developers']) or $this->modelProperties['AuthorInfs']['Developers'] = $authorinfs['developers'];
		empty($authorinfs['homepage']) or $this->modelProperties['AuthorInfs']['Homepage'] = $authorinfs['homepage'];
	}

	private function loadRequires(array $requires){
		$array = [];
		if(isset($requires['nik'])){
			foreach ($requires['nik'] as $value) {
				$array[] = CPATH.$value;
			}
		}
		if(isset($requires['lib'])){
			foreach ($requires['lib'] as $value) {
				$array[] = LIB_PATH.$value;
			}
		}
		if(isset($requires['xtp'])){
			foreach ($requires['lib'] as $value) {
				$array[] = LIB_PATH.$value;
			}
		}
		if(isset($requires['stl'])){
			$path = $this->suit_path;
			foreach ($requires['stl'] as $value) {
				$array[] = $path.$value;
			}
		}
		if(isset($requires['app'])){
			$path = $this->installPath;
			foreach ($requires['app'] as $value) {
				$array[] = $path.$value;
			}
		}
		$this->modelProperties['Requires'] = $array;
	}
}
