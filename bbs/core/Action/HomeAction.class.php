<?php
class HomeAction extends CmsAction{
	public function _init(){
		parent::_init();
		if(config('web_close')){
			$this->error(config('web_closecon'));
		}
		import('class/robot');
		$robot=new robot;
		if($robot->check()){
			$data['is_robot']=true;
		}
		if(config('web_robot_onnotes')) $robot->notes();
		if(APP_DEBUG or config('web_debug')){
			$this->tplConf('compile_check',true);
			$this->tplConf('caching',false);
			config('TMPL_COMPILE_CHECK',true);
			config('TMPL_HTML_CACHE',false);
		}
		//获取广告代码
		$adlist=DB('myad')->select();
		foreach($adlist as $k=>$vo){
			$mark=$adlist[$k]['mark'];
			$data['myad'][$mark]=$adlist[$k]['code'];
		}
		$data['web_path']=__ROOT__;
		$data['theme_path']=__ROOT__.'/template/'.config('default_theme');
		$this->tplConf('template_dir',TMPL_PATH.config('default_theme'));
		$this->assign($data);
	}
}