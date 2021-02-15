<?php
/**
 * TXTCMS 项目配置
 * @copyright			(C) 2013-2014 TXTCMS
 * @license				http://www.txtcms.com
 * @lastmodify			2014-8-8
 */
defined('INI_TXTCMS') or exit();
$config = require TEMP_PATH.'config.php';
$array = array(
	'ROBOT_FILE'=>LOG_PATH.'robots.log',
	'DEFAULT_GROUP'=>'Home',
	'DEFAULT_THEME'=>'default',
	'APP_GROUP_LIST'=>'Home,Admin',	 //项目分组，多个用逗号隔开
	'DB_HASH_LIST'=>'arcbody',	 //分表名，多个用逗号隔开
	'DEFAULT_THEME'=>$config['default_theme'],
	'URL_MODEL'=>$config['web_url_model'],
	'URL_PATH_DEPR'=>$config['web_path_depr'],
	'URL_PATH_SUFFIX'=>$config['web_path_suffix'],
	'TMPL_HTML_CACHE' =>$config['web_caching'],
);
return array_merge($config,$array);