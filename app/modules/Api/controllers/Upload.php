<?php
/**
 * Created by PhpStorm.
 * User: DELL
 * Date: 2019/07/28
 * Time: 20:57
 * Email: 574482856@qq.com
 */


defined('APP_PATH') OR exit('No direct script access allowed');

class UploadController extends ApiBaseController {

  const fileType = [

    'thumb' => ['jpg', 'png', 'jpeg', 'gif', 'bmp', 'swf'],

    'file' => ['doc', 'docx', 'xls', 'xlsx', 'ppt', 'pdf', 'zip', 'rar', 'xsv', 'txt']
  ];

  /**
   * 针对百度编辑器上传
   * @method ueditorAction
   * 2019/7/28 9:47
   */
  public function ueditorAction() {

    $action = $this->_get('action');

    switch ($action) {

      case 'config':
        $config = json_decode(preg_replace("/\/\*[\s\S]+?\*\//", "", $this->getConfig()), TRUE);
        echo jsonencode($config);
        exit;
        break;
      case 'listimage':

        break;
      case 'catchimage':

        break;
      case 'uploadfile':
        return $this->uploadfile();
        break;
      case 'uploadvideo':
        return $this->uploadfile();
        break;
      case 'uploadimage':
        return $this->uploadfile();
        break;
      default : //uploadfile  uploadvideo  uploadimage

        $data = ['state' => '上传失败'];
        break;
    }

    return $this->baseService->show($data, $data ? API_SUCCESS : API_FAILURE);
  }

  /**
   * 获取config文件
   * @method getConfig
   * @return string
   * 2019/7/28 11:13
   */
  private function getConfig() {

    $json = <<<EOT
/* 前后端通信相关的配置,注释只允许使用多行方式 */
{
    /* 上传图片配置项 */
    "imageActionName": "uploadimage", /* 执行上传图片的action名称 */
    "imageFieldName": "upfile", /* 提交的图片表单名称 */
    "imageMaxSize": 2048000, /* 上传大小限制，单位B */
    "imageAllowFiles": [".png", ".jpg", ".jpeg", ".gif", ".bmp"], /* 上传图片格式显示 */
    "imageCompressEnable": true, /* 是否压缩图片,默认是true */
    "imageCompressBorder": 1600, /* 图片压缩最长边限制 */
    "imageInsertAlign": "none", /* 插入的图片浮动方式 */
    "imageUrlPrefix": "", /* 图片访问路径前缀 */
    "imagePathFormat": "/ueditor/php/upload/image/{yyyy}{mm}{dd}/{time}{rand:6}", /* 上传保存路径,可以自定义保存路径和文件名格式 */
                                /* {filename} 会替换成原文件名,配置这项需要注意中文乱码问题 */
                                /* {rand:6} 会替换成随机数,后面的数字是随机数的位数 */
                                /* {time} 会替换成时间戳 */
                                /* {yyyy} 会替换成四位年份 */
                                /* {yy} 会替换成两位年份 */
                                /* {mm} 会替换成两位月份 */
                                /* {dd} 会替换成两位日期 */
                                /* {hh} 会替换成两位小时 */
                                /* {ii} 会替换成两位分钟 */
                                /* {ss} 会替换成两位秒 */
                                /* 非法字符 \ : * ? " < > | */
                                /* 具请体看线上文档: fex.baidu.com/ueditor/#use-format_upload_filename */

    /* 涂鸦图片上传配置项 */
    "scrawlActionName": "uploadscrawl", /* 执行上传涂鸦的action名称 */
    "scrawlFieldName": "upfile", /* 提交的图片表单名称 */
    "scrawlPathFormat": "/ueditor/php/upload/image/{yyyy}{mm}{dd}/{time}{rand:6}", /* 上传保存路径,可以自定义保存路径和文件名格式 */
    "scrawlMaxSize": 2048000, /* 上传大小限制，单位B */
    "scrawlUrlPrefix": "", /* 图片访问路径前缀 */
    "scrawlInsertAlign": "none",

    /* 截图工具上传 */
    "snapscreenActionName": "uploadimage", /* 执行上传截图的action名称 */
    "snapscreenPathFormat": "/ueditor/php/upload/image/{yyyy}{mm}{dd}/{time}{rand:6}", /* 上传保存路径,可以自定义保存路径和文件名格式 */
    "snapscreenUrlPrefix": "", /* 图片访问路径前缀 */
    "snapscreenInsertAlign": "none", /* 插入的图片浮动方式 */

    /* 抓取远程图片配置 */
    "catcherLocalDomain": ["127.0.0.1", "localhost", "img.baidu.com"],
    "catcherActionName": "catchimage", /* 执行抓取远程图片的action名称 */
    "catcherFieldName": "source", /* 提交的图片列表表单名称 */
    "catcherPathFormat": "/ueditor/php/upload/image/{yyyy}{mm}{dd}/{time}{rand:6}", /* 上传保存路径,可以自定义保存路径和文件名格式 */
    "catcherUrlPrefix": "", /* 图片访问路径前缀 */
    "catcherMaxSize": 2048000, /* 上传大小限制，单位B */
    "catcherAllowFiles": [".png", ".jpg", ".jpeg", ".gif", ".bmp"], /* 抓取图片格式显示 */

    /* 上传视频配置 */
    "videoActionName": "uploadvideo", /* 执行上传视频的action名称 */
    "videoFieldName": "upfile", /* 提交的视频表单名称 */
    "videoPathFormat": "/ueditor/php/upload/video/{yyyy}{mm}{dd}/{time}{rand:6}", /* 上传保存路径,可以自定义保存路径和文件名格式 */
    "videoUrlPrefix": "", /* 视频访问路径前缀 */
    "videoMaxSize": 102400000, /* 上传大小限制，单位B，默认100MB */
    "videoAllowFiles": [
        ".flv", ".swf", ".mkv", ".avi", ".rm", ".rmvb", ".mpeg", ".mpg",
        ".ogg", ".ogv", ".mov", ".wmv", ".mp4", ".webm", ".mp3", ".wav", ".mid"], /* 上传视频格式显示 */

    /* 上传文件配置 */
    "fileActionName": "uploadfile", /* controller里,执行上传视频的action名称 */
    "fileFieldName": "upfile", /* 提交的文件表单名称 */
    "filePathFormat": "/ueditor/php/upload/file/{yyyy}{mm}{dd}/{time}{rand:6}", /* 上传保存路径,可以自定义保存路径和文件名格式 */
    "fileUrlPrefix": "", /* 文件访问路径前缀 */
    "fileMaxSize": 51200000, /* 上传大小限制，单位B，默认50MB */
    "fileAllowFiles": [
        ".png", ".jpg", ".jpeg", ".gif", ".bmp",
        ".flv", ".swf", ".mkv", ".avi", ".rm", ".rmvb", ".mpeg", ".mpg",
        ".ogg", ".ogv", ".mov", ".wmv", ".mp4", ".webm", ".mp3", ".wav", ".mid",
        ".rar", ".zip", ".tar", ".gz", ".7z", ".bz2", ".cab", ".iso",
        ".doc", ".docx", ".xls", ".xlsx", ".ppt", ".pptx", ".pdf", ".txt", ".md", ".xml"
    ], /* 上传文件格式显示 */

    /* 列出指定目录下的图片 */
    "imageManagerActionName": "listimage", /* 执行图片管理的action名称 */
    "imageManagerListPath": "/ueditor/php/upload/image/", /* 指定要列出图片的目录 */
    "imageManagerListSize": 20, /* 每次列出文件数量 */
    "imageManagerUrlPrefix": "", /* 图片访问路径前缀 */
    "imageManagerInsertAlign": "none", /* 插入的图片浮动方式 */
    "imageManagerAllowFiles": [".png", ".jpg", ".jpeg", ".gif", ".bmp"], /* 列出的文件类型 */

    /* 列出指定目录下的文件 */
    "fileManagerActionName": "listfile", /* 执行文件管理的action名称 */
    "fileManagerListPath": "/ueditor/php/upload/file/", /* 指定要列出文件的目录 */
    "fileManagerUrlPrefix": "", /* 文件访问路径前缀 */
    "fileManagerListSize": 20, /* 每次列出文件数量 */
    "fileManagerAllowFiles": [
        ".png", ".jpg", ".jpeg", ".gif", ".bmp",
        ".flv", ".swf", ".mkv", ".avi", ".rm", ".rmvb", ".mpeg", ".mpg",
        ".ogg", ".ogv", ".mov", ".wmv", ".mp4", ".webm", ".mp3", ".wav", ".mid",
        ".rar", ".zip", ".tar", ".gz", ".7z", ".bz2", ".cab", ".iso",
        ".doc", ".docx", ".xls", ".xlsx", ".ppt", ".pptx", ".pdf", ".txt", ".md", ".xml"
    ] /* 列出的文件类型 */

}
EOT;

    return $json;
  }

  /**
   * 图片上传
   * @method uploadfile
   * @return array
   * 2019/7/28 10:48
   */
  private function uploadfile() {
    $file = new File($_FILES['upfile']['tmp_name']);

    $file->rule('');
    $result = $file->setUploadInfo($_FILES['upfile'])->validate(['size' => 26214400])->move(UPLOAD_PATH);

    if ($result) {
      $data = [
        'state' => '上传成功',
        'url' => rtrim(VIEW_HOST, '//') . DS . trim(VIEW_PATH, '//') . DS . ltrim($result->getSaveName(), '//'),
        'extension' => $result->getExtension(),
        'title' => $result->getFilename(),
        'original' => $result->getFilename(),
      ];

    } else {
      $data = ['state' => '上传失败'];
    }

    return $this->baseService->show($data, $data ? API_SUCCESS : API_FAILURE);
  }

  /**
   * 文件上传，需要进行授权才可以上传
   * @return array
   */
  public function indexAction() {

    $type = $this->_post('type');

    if (!key_exists($type, self::fileType)) {
      showApiException('传递类型不正确');
    }

    $extExtension = self::fileType[$type];

    $file = new File($_FILES['uploadFile']['tmp_name']);

    $file->rule('');

    $result = $file->setUploadInfo($_FILES['uploadFile'])->validate(['size' => 26214400, 'ext' => implode(',', $extExtension)])->move(UPLOAD_PATH . DS . $type);

    if ($result) {
      $data = [
        'path' => rtrim(VIEW_PATH, '//') . DS . $type . DS . ltrim($result->getSaveName(), '//'),
        'extension' => $result->getExtension(),
        'filename' => $result->getFilename(),
        'host' => rtrim(VIEW_HOST, '//')
      ];
    } else
      $data = ['errMsg' => $result->getError()];

    return $this->baseService->show($data, $data ? API_SUCCESS : API_FAILURE);
  }


}