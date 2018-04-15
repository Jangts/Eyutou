<?php
// 应用框架的路由器基类所使用的命名空间
namespace AF\Routers;

// 引入相关命名空间，以简化书写
use Status;
use Request;
use App;

/**
 * @class AF\Routers\RegExpRouter
 * 路由器基类
 * 
 * @author     Jangts
 * @version    5.0.0
**/
class RegExpRouter extends BaseRouter {    
    private static function replacePattern($str, $encodePattern){
        $str ='/'.$str;
        $str = preg_replace('/(\/|\\\)+/', '\\/', $str);
        $str = preg_replace($encodePattern, '\\\$1', $str);

		$str = str_replace('<*>', '(\S*)', $str);
		$str = str_replace('<a>', '([A-z]+)', $str);
		$str = str_replace('<0>', '([0-9]+)', $str);
		$str = str_replace('<a9>', '([A-z0-9]+)', $str);
        $str = str_replace('<w>', '([A-z0-9-_]+)', $str);
        $str = str_replace('<0f>', '([\x00-\xff]+)', $str);
        $str = str_replace('<u>', '([^\\\\\/\r\n]+)', $str);
        if((strrchr($str, '(\S*)') != '(\S*)')&&(strrchr($str, '\/') != '\/')){
            $str .= '\\/';
        }
		return '/^'.$str.'$/i';
    }

    private static function generatePatterns($patterns){
        if(0){
            # 读取缓存
        }else{
            $encodePatternArray = ['.', '(', ')', '[', ']', '{', '}', '^', '+', '?'];
            $encodePatternString = '/(\\' . implode('|\\', $encodePatternArray) . ')/';
            $pregPatterns = [];
            foreach($patterns as $pattern=>$options){
                $options["pattern"] = self::replacePattern($pattern, $encodePatternString);
                $pregPatterns[] = $options;
            }
        }
        return $pregPatterns;
    }

    private static function matchPatterns($path, $obj){
        $patterns = self::generatePatterns($obj->patterns);
        foreach($patterns as $index=>$options){
            $result = preg_match($options["pattern"], $path, $matches);
            if($result){
                array_shift($matches);
                $matches = array_combine($options['matchkeys'], $matches);
                return [$options, $matches];
            }
        }
        // 匹配失败，交由相关函数处理
        $obj->resourceNotFound($path);
    }
    
    protected function resourceNotFound($path){
        new Status(404, '', 'Resource ' . $path . ' not found.', true);
    }

    /**
     * 路由分析方法
     * 
     * @access protected
     * @final
     * @param object(Tangram\MODEL\Application) $app
     * @param object(Tangram\MODEL\Request)     $request
     * @return array
    **/
    final protected function analysis(App $app, Request $request) : array {
        $path = '/' . implode('/', $request->ARI->patharr) . '/';
        list($options, $matches) = self::matchPatterns($path, $this);

        $classname = self::correctClassName($this->getClassName($options, $matches));
		$filename = $app->Path.'Controllers/'.str_replace('\\', '/', $classname);
		$fullclassname = '\\'.$app->xProps['Namespace'].'\\Controllers\\'.$classname;
		$methodname = $this->getMethodName($options, $matches);
        return [$filename, $fullclassname, $methodname, [$matches]];
    }
    
    final protected function getClassName(array $options, array $matches) : string {
        if(!empty($options['controller'])){
            return $options['controller'];
        }
        if(!empty($matches['controller'])){
            return $matches['controller'].'Controller';
        }
        new Status(1415.1, '', 'Classname Unspecified', true);
    }

    final protected function getMethodName(array $options, array $matches) : string {
        if(!empty($options['method'])){
            return $options['method'];
        }
        if(!empty($matches['method'])){
            return $matches['method'];
        }
        new Status(1415.3, '', 'Methodname Unspecified', true);
    }
}