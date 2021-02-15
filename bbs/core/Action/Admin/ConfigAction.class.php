<?php
/**
 * TXTCMS 网站配置模块
 * @copyright			(C) 2013-2014 TXTCMS
 * @license			http://www.txtcms.com
 * @lastmodify			2014-8-8
 */
class ConfigAction extends AdminAction {
	public $confile;
	public function _init(){
		parent::_init();
		$this->confile=TEMP_PATH.'config.php';
	}
	//显示模板
	public function index(){
		$theme_list=array();
		$tplpath=TMPL_PATH;
		$dirarr=scandirs($tplpath);
		foreach($dirarr as $k=> $file){
			if(is_dir($tplpath.$file) && $file!='.' && $file!='..' ){
				$theme_list[$k]['dir']=$dirarr[$k];
			}
		}
		$this->assign('themelist',$theme_list);
		$this->display();
	}
	//更新配置
	public function update(){
		$config = $_POST["con"];
		foreach( $config as $k=> $v ){
			$config[$k]=trim($config[$k]);
			$config[$k]=get_magic($config[$k]);
		}
		$ajax=array();
		if(!preg_match("#http://[^/]+/$#",$config['web_url'])){
			$ajax['status']=0;
			$ajax['info']='网站地址格式不正确';
			$this->ajaxReturn($ajax);
		}else{
			$ajax['status']=1;
		}
		$config_old = require $this->confile;
		$config_new =array_merge($config_old,$config);
		ksort($config_new);
		arr2file($this->confile,$config_new);
		$this->ajaxReturn($ajax);
	}
}