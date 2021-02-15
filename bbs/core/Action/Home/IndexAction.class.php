<?php
/**
 * TXTCMS 首页模块
 * @copyright			(C) 2013-2014 TXTCMS
 * @license				http://www.txtcms.com
 * @lastmodify			2014-8-8
 */
class IndexAction extends HomeAction {
	public function Index(){
		$this->tplConf('cache_lifetime',config("cache_lifetime_index")*3600);
		if(config("cache_lifetime_index")==0) $this->tplConf('caching',false);
		$this->display();
	}
}