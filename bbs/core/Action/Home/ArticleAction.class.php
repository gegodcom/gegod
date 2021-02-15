<?php
/**
 * TXTCMS 文章模块
 * @copyright			(C) 2013-2014 TXTCMS
 * @license				http://www.txtcms.com
 * @lastmodify			2014-8-8
 */
class ArticleAction extends HomeAction {
	public $Arctype;
	public $Article;
	public function _init(){
		parent::_init();
		$this->Arctype=DB('arctype');
		$this->Article=DB('article');
	}
	public function Index(){
		$this->display();
	}
	public function lists(){
		$data=array();
		$id=isset($_GET['id'])?intval($_GET['id']):'0';
		$this->tplConf('cache_lifetime',config("cache_lifetime_channel")*3600);
		if(config("cache_lifetime_channel")==0) $this->tplConf('caching',false);
		$cache_id=md5($_SERVER['QUERY_STRING']);
		$data=$this->Arctype->where('id='.$id)->find();
		if($data){
			//获取上级分类信息
			$result=$this->Arctype->where('id='.$list['pid'])->find();
			if($result){
				$data['pname']=$result['cname'];
			}
			if($id=='0') $data['cname']='所有文章';
			$data['cid']=$id;
			$this->assign($data);
		}else{
			$this->error('栏目不存在');
		}
		$this->display('',$cache_id);
	}
	public function show(){
		$data=array();
		$id=isset($_GET['id'])?intval($_GET['id']):$this->error('id不能为空');
		$this->tplConf('cache_lifetime',config("cache_lifetime_view")*3600);
		if(config("cache_lifetime_view")==0) $this->tplConf('caching',false);
		$cache_id=md5($_SERVER['QUERY_STRING']);
		$data=$this->Article->where('id='.$id)->find();
		if(!$data) $this->error('文章不存在');
		$class=$this->Arctype->where('id='.$data['cid'])->find();
		$data['cname']=$class['cname'];
		$data['thisurl']=url('Article/show?id='.$id);
		$data['curl']=url('Article/lists?id='.$data['cid']);
		$result=DB('arcbody')->getHash($data['id'],true)->where('id='.$data['id'])->find();
		if($result) $data['body']=$result['body'];
		$this->assign($data);
		$this->display('',$cache_id);
	}
	public function search(){
		$data=array();
		$this->tplConf('caching',false);
		$data['title']=isset($_REQUEST['q'])?htmlspecialchars($_REQUEST['q']):$this->error('关键词不能为空');
		$cache_id=md5($_SERVER['QUERY_STRING']);
		$this->assign($data);
		$this->display('',$cache_id);
	}
}