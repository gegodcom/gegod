<?php
/**
 * TXTCMS 框架视图类
 * @copyright			(C) 2013-2014 TXTCMS
 * @license				http://www.txtcms.com
 * @lastmodify			2014-8-8
 */
class View {
	public $smarty;
	private $group;
	function __construct(){
		$this->group=defined('GROUP_NAME')?GROUP_NAME.'/':'';
		$theme=$this->getTemplateTheme();
		$this->smarty=new Smarty;
		$this->smarty->tpl_path=config('TMPL_PATH') ? config('TMPL_PATH') : TMPL_PATH;
		$this->smarty->debugging=false;
		$this->smarty->compile_check=config('TMPL_COMPILE_CHECK');
		$this->smarty->addPluginsDir(TEMPLATE_PATH.'plugins');
		$this->smarty->template_dir=$this->smarty->tpl_path.$this->group.$theme;
		$this->smarty->compile_dir=TPLCACHE_PATH.$this->group.$theme;	//编译目录
		$this->smarty->caching=config('TMPL_HTML_CACHE');	//缓存开关
		$this->smarty->cache_dir=CACHE_PATH.$this->group.$theme;	//缓存目录
		$this->smarty->use_sub_dirs=false;
		$this->smarty->left_delimiter='{';	//左边界符
		$this->smarty->right_delimiter='}';	//右边界符
	}
	/**
     * 模板变量赋值
     * @access public
     * @param mixed $name
     * @param mixed $value
     */
    public function assign($name,$value=''){
		$this->smarty->assign($name,$value);
    }
	/**
     * 获取模板变量的值
     * @access public
     * @param mixed $name
     * @param mixed $value
     */
    public function get($name=''){
		$this->smarty->getTemplateVars($name);
    }
	//获取当前操作模板
	public function getTemplate(){
		//获取当前主题名称
		if(ACTION_NAME==config('DEFAULT_ACTION')){
			$template=MODULE_NAME;
		}else{
			$template=MODULE_NAME.'_'.ACTION_NAME;
		}
		$group=$this->group;
		if($this->group==config('DEFAULT_GROUP').'/'){
			$group='';
		}
		define('THEME_PATH',$this->smarty->tpl_path.$group.$this->getTemplateTheme());
		$template=THEME_PATH.$template.config('TMPL_TEMPLATE_SUFFIX');
		return $template;
	}
	/**
     * 模板输出
     * @access public
     * @param string $templateFile 模板文件名
     */
	public function display($templateFile='',$cacheid=''){
		if(!is_file($templateFile)) {
			$templateFile=$this->getTemplate();
		}
		if($this->smarty->caching){
			$_cache_dirs=getHashDir($cacheid);
			$this->smarty->cache_dir.=$_cache_dirs;
		}
		$this->smarty->display('file:'.$templateFile,$cacheid);
	}
	public function __call($name,$args){
		$method=new ReflectionMethod($this->smarty,$name);
		$method->invokeArgs($this->smarty,$args);
	}
	/**
     * 获取当前的模板主题
     * @access private
     * @return string
     */
    private function getTemplateTheme() {
		$theme=config('DEFAULT_THEME');
        define('THEME_NAME',$theme); // 当前模板主题名称
        return $theme ? $theme.'/':'';
    }
}