<?php
/**
 * Created by PhpStorm.
 * User: songyongzhan
 * Date: 2018/10/17
 * Time: 13:32
 * Email: songyongzhan@qianbao.com
 */

define('TEMPLATE_DIR', APP_PATH . '/static/views');

define('DS', DIRECTORY_SEPARATOR);
define('APP_CONFIG_PATH', APP_PATH . DS . 'app/configs');


define('API_SUCCESS', 200000);
define('API_FAILURE', 999999);
define('API_FAILURE_MSG', '系统错误请联系管理员!');
define('FETCH_DUMMY', FALSE); //是否模拟数据


/**
 * 配置型常量
 */
define('TWIG_INIT_FLAG', TRUE);
define('DB_INIT_FLAG', TRUE);

//定义错误页面存放路径
define('ERROR_TEMPLATE_PATH', APP_PATH . DS . 'static/_common/errors');
define('ERROR_FILENAME', 'error_general.php');
define('EXCEPTION_FILENAME', 'exception_general.php');

define('PREFIX', 'api_'); //设置时，前缀是app_