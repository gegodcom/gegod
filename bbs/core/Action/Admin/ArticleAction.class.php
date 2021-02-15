<?php
/**
 * TXTCMS 文章管理模块
 * @copyright			(C) 2013-2014 TXTCMS
 * @license				http://www.txtcms.com
 * @lastmodify			2014-8-8
 */
class ArticleAction extends AdminAction {
	public $Article;
	public $Arctype;
	public $Arcbody;
	public function _init(){
		parent::_init();
		$this->Article=DB('Article');
		$this->Arctype=DB('arctype');
		$this->Arcbody=DB('arcbody');
	}
	public function index(){
		$zongshu=$this->Article->count();
		$q=isset($_GET['q'])?urldecode($_GET['q']):'';
		$p=isset($_GET['p'])?intval($_GET['p']):1;
		$cid=isset($_GET['cid'])?intval($_GET['cid']):'';
		if($p<1) $p=1;
		$limit=10;//分页大小
		$where=array('cid>0');
		if($cid!=''){
			$where[]='and';
			$where[]='cid='.$cid;
		}
		if($q!=''){
			$where[]='and';
			$where[]='title=~%'.$q.'%';
		}
		if(isset($_GET['isshow'])){
			$where[]='and';
			$where[]='isshow='.$_GET['isshow'];
		}
		if(isset($_GET['flag'])){
			$where[]='and';
			if($_GET['flag']=='litpic'){
				$where[]='!empty(litpic)';
			}else{
				$where[]='flag=~%'.$_GET['flag'].'%';
			}
		}
		$result=$this->Article->where($where)->order('id desc')->select();
		$guolv=count($result);
		if($guolv>0){
			$totalpages = ceil($guolv/$limit);
			if($p > $totalpages){
				$p = $totalpages;
			}
			$startp = ($p-1) * $limit;
			$result=array_slice($result,$startp,$limit);
			foreach($result as $key=>$vo){
				$result[$key]['addtime']=date('Y-m-d H:i:s',$vo['addtime']);
				if(date("Y-m-d")==date("Y-m-d",strtotime($result[$key]['addtime']))) $result[$key]['addtime']='<font color=red>'.$result[$key]['addtime'].'</font>';
				$result[$key]['isshow']=($vo['isshow']==1)?'<font color=#666666>已审核</font>':'<font color=red>未审核</font>';
				if($q!=''){
					$result[$key]['title']=str_ireplace($q,'<font color=red>'.$q.'</font>',$result[$key]['title']);
				}
				if($result[$key]['style']!='') $result[$key]['title']='<font style='.$result[$key]['style'].'>'.$result[$key]['title'].'</font>';
				$arctype=$this->Arctype->where('id='.$vo['cid'])->find();
				//获取分类
				if(isset($_GET['isshow'])){
					$result[$key]['cname']="<a href='?Admin-Article-cid-{$vo['cid']}-show-{$_GET['isshow']}'>".$arctype['cname'].'</a>';
				}else{
					$result[$key]['cname']="<a href='?Admin-Article-cid-{$vo['cid']}'>".$arctype['cname'].'</a>';
				}
				//判断是否有缩略图
				if($result[$key]['litpic']!='') $result[$key]['title']=$result[$key]['title'].'<font color=#ff3300>[图]</font>';
			}
			if ($q) {
				$pages = get_page_css($p, $totalpages, 4,url("Admin/Article/index?p=!page!"), false);
			} else {
				$pages = get_page_css($p, $totalpages, 4,url("Admin/Article/index?p=!page!&q=".$q), false);
			}
		}
		$_SESSION['Archives_reurl']=url("Admin/Article/index?p=".$p);
		$data['totalpages']=$totalpages;
		$data['total']=$guolv;
		$data['list']=$result;
		$data['p']=$p;
		$data['pages']=$pages;
		$this->assign($data);
		$this->display();
	}
	public function edit(){
		$id=isset($_GET['id'])?intval($_GET['id']):0;
		$data=array();
		if($id>0){
			$where='id='.$id;
			$data=$this->Article->where($where)->find();
			$result=$this->Arcbody->getHash($id,true)->where($where)->find();
			if($result) $data['body']=$result['body'];
		}else{
			$data['default_day']=date('Y-m-d H:i:s');
			$data['cid']=isset($_GET['cid'])?$_GET['cid']:0;
		}
		$class=$this->Arctype->select();
		$class_option=channel_option_tree($class,0,$data['cid'],0);
		$data['class_option']=$class_option;
		$this->assign($data);
		$this->display();
	}
	public function update(){
		$config=$_POST['con'];
		foreach( $config as $k=> $v ){
			$config[$k]=get_magic(trim($config[$k]));
		}
		$config['title']=htmlspecialchars($config['title']);
		if($config['title']=='') $this->ajaxReturn(array('status'=>0,'info'=>'标题不能为空！'));
		$config['style']=$config['style2'].$config['style1'];
		if($config['id']>0){
			$config['addtime']=strtotime($config['addtime']);
			if($config['description']=='') $config['description']=msubstr(strip_tags($config['body']),0,100,'utf-8','');
			$result=$this->Article->where('id='.$config['id'])->data($config)->save();
			$last_id=$config['id'];
		}else{
			$config['addtime']=time();
			if($config['keywords']=='') $config['keywords']=$config['title'];
			if($config['description']=='') $config['description']=msubstr(strip_tags($config['body']),0,100,'utf-8','');
			$last_id=$result=$this->Article->data($config)->add();
		}
		//保存到内容表，返回的result是ID
		if(is_numeric($last_id)){
			//先删除后保存
			$this->Arcbody->getHash($last_id,true)->where('id='.$last_id)->delete();
			$result_b=$this->Arcbody->getHash($last_id,true)->data(array('body'=>$config['body'],'id'=>$last_id))->add();
			if($result_b===false) $this->ajaxReturn(array('status'=>0,'info'=>'(id:'.$last_id.') 保存到内容表失败！'));
		}
		if($result){
			$this->ajaxReturn(array('status'=>1,'url'=>$_SESSION['Archives_reurl']));
		}else{
			$this->ajaxReturn(array('status'=>0,'info'=>'操作失败！'));
		}
	}
	public function del(){
		$id=isset($_GET['id'])?intval($_GET['id']):$this->error('id 不能为空');
		$result=$this->Article->where('id='.$id)->delete();
		$result_b=$this->Arcbody->getHash($id,true)->where('id='.$id)->delete();
		if(!$result) $this->error('删除失败！');
		$this->success('删除成功！',$_SESSION['Archives_reurl']);
	}
	public function delmore(){
		$ids=!empty($_POST['ids'])?$_POST['ids']:$this->error('未选中文档');
		foreach($ids as $k=>$vo){
			$this->Article->where('id='.$vo)->delete();
			$this->Arcbody->getHash($vo,true)->where('id='.$vo)->delete();
		}
		$this->success('删除成功！',$_SESSION['Archives_reurl']);
	}
}