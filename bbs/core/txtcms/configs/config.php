<?php
/**
 * TXTCMS 框架配置
 * @copyright			(C) 2013-2014 TXTCMS
 * @license				http://www.txtcms.com
 * @lastmodify			2014-8-8
 */
defined('INI_TXTCMS') or exit();
return array(
	'URL_MODEL'=>2,
	'URL_PATH_DEPR'=>'-',
	'URL_PATH_SUFFIX'=>'html',
	'MODULE_VAR'=>'m',
	'ACTION_VAR'=>'a',
	'GROUP_VAR'=>'g',
	'DEFAULT_GROUP'=>'',
	'DEFAULT_THEME'=>'default',
	'DEFAULT_MODULE'=>'Index',
	'DEFAULT_ACTION'=>'index',
	'DEFAULT_TIMEZONE'=> 'PRC',
	'DEFAULT_FILTER'=>'htmlspecialchars',
	'DEFAULT_C_SUFFIX'=>'Action',
	'APP_GROUP_LIST'=>'',	 //项目分组，多个用逗号隔开
	'URL_404_REDIRECT'=>'',	//404跳转页
	'DEFAULT_FILTER'=>'htmlspecialchars',
	'ERROR_PAGE'=>'',//错误跳转页
	'SHOW_ERROR_MSG'=>false,//是否显示错误信息
	'ERROR_MESSAGE'=>'页面发生异常错误！',
	'DB_HASH_LIST'=>'', //需要分表的数据库表，多个用逗号隔开
	/* 模板引擎设置 */
    'TMPL_ACTION_ERROR'     => TXTCMS_PATH.'tpl/_jump.html', // 默认错误跳转对应的模板文件
    'TMPL_ACTION_SUCCESS'   => TXTCMS_PATH.'tpl/_jump.html', // 默认成功跳转对应的模板文件
    'TMPL_EXCEPTION_FILE'   => TXTCMS_PATH.'tpl/_exception.html',// 异常页面的模板文件
    'TMPL_TEMPLATE_SUFFIX'  => '.html',     //默认模板文件后缀
    'TMPL_FILE_DEPR'=>'-', //模板文件MODULE_NAME与ACTION_NAME之间的分割符
	'TMPL_COMPILE_CHECK'=>false,	//模板编译检测
	'TMPL_HTML_CACHE'=>false, //静态缓存开关
);