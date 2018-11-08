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

define('MODULES_PATH', APP_PATH . DS . 'app/modules'); //多模块位置


define('COOKIE_KEY', 'asfd654987'); //10

define('API_SUCCESS', 200000);
define('API_FAILURE', 999999);
define('API_FAILURE_MSG', '系统错误请联系管理员!');

//是否模拟数据 如果没有定义(index.php入口文件)，则这里定义
defined('FETCH_DUMMY') || define('FETCH_DUMMY', FALSE);


switch (ENVIRONMENT) {
  case 'develop': //开发配置文件
    define('REMOTE_HOST', 'http://sit1-apis.qianbao.com');
    define('RESTHUB_SERVER_PUBLIC',
      'MIGfMA0GCSqGSIb3DQEBAQUAA4GNADCBiQKBgQCKDywLndrEAaUaB3tDPpdNwJ+cTHnMEa2tVJtC+sY7IhyooEnb1m/7b+t92GZTC0JJeK5zC99RUvgDPv8C9ChQPSU6ecKlBH/RNOci5GnR33d4a0dBhzKtSq8eWPIuAMB9IkyGCYeqV938dKcuCscdG29MsHRMFfwwbWYv8QeaiQIDAQAB');
    define('RESTHUB_CLIENT_PRIVKEY',
      'MIICdgIBADANBgkqhkiG9w0BAQEFAASCAmAwggJcAgEAAoGBAKfP2gYBvfVXTX/dOXGJJSvIFexooRqw3BBfdkMoBiEJfjCToWFqIyUDEowDFpUfVZ2Yc0tCZqrryS2KISZtc+QQozIG662lXyZ6hAM/r+L2KUOKMFxNJQm8d3UeSDO5lxM9d0DrG7TNWTZf1+nUDljhoJGyRcExWo980jqsedefAgMBAAECgYEAgmWbp+FAp10AZqQTl+qWzK98gahHz4KwbbSQI9z87jz/JmYBF74usvrxNYTMznF7yKsGo+tj9dqkB9P2sHKKguAN4fyCJIFYzHj23ph23vyxYbR7aA+lD51fXovoD/P39EixKXWdDYl7RTb9jCCrdfJ6HglVMSga/1iXhYBnPmkCQQDptb9+EI97ofRsiIaEi3jWE6B//8hrd7sggcYq+taEfVfdjQhE7lFTeTgfEt/tH/pPY7D06imyvNZYTiZ7KBKlAkEAt9Ej4VNRgFe59z5X8Rb0ewqM4vGC3rNMRkZJZ1KDL66PXr3MAwIisMAsfXOpN3iEskJs1HIVy+aDDJVCvO+B8wJAF6LN7w31tOc4NRHJqPYCDoSFouxXdKbzQeJeDFK6B0Q18q4ku/PuPabwyhO6mdy2D/lhGCPme7ElbGDa+3GeEQJAWdzFhLd9xZedk3CH/5XwSWKcA6p8BzFyXXypD/j3p0zYTEHPRb06hlw8o8vycurZPGha2fU4EKmNcY5axRD13wJAa0aoGwXthEc2mzmkn5tvnWRhgofR0yqBrcHSKl3NlkAHFL1t+P8X53hDyfs/V7IESOlxlJXuxysSPjTVedV4Jw==');

    break;
  case 'testing': //测试配置文件

    define('REMOTE_HOST', 'http://sit1-apis.qianbao.com');
    break;
  case 'product': //生产配置文件

    define('REMOTE_HOST', '');

    break;
}


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

define('PAGESIZE', 10);