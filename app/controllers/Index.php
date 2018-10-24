<?php

/**
 * @name IndexController
 * @author root
 * @desc 默认控制器
 * @see http://www.php.net/manual/en/class.yaf-controller-abstract.php
 */
class IndexController extends BaseController {


  public function detailAction(){


    $this->assign('user','james');
    $this->getView()->display('/index/detail.html');
    //echo $this->_render('/index/detail.html');

    new SampleModel();


  }


  public function bbAction() {

    /*   debugMessage('debug 日志');
       logMessage('info','asdfadsfas');
       logMessage('warning','warningwarningwarningwarningwarningwarningwarningwarningwarningwarning');
       logMessage('debug',['name'=>'james','age'=>'33']);*/


    //return json_encode(['name'=>'james','age'=>'33']);

    echo json_encode(['name'=>'james','age'=>'33']);
    return true;
    //$this->getResponse()->setBody('content', ['name' => 'james', 'age' => '33']);


    //var_dump($vv instanceof $this);
    /*   $requet = Yaf_Application::app()->getDispatcher()->getRouter();
       $relation = new ReflectionClass($this);

       P($relation->getReflectionConstants());*/

    //throw new Exception('132456');
  }

  public function testAction() {
    echo 'test';
  }


  public function vvAction() {
    //var_dump(Tools_Request::getRequest()->getModuleName());
    $this->_name;
    $model = new SampleModel();

    Yaf_Application::app()->getDispatcher()->getRouter();


    P($this->getParams());

    P(Yaf_Application::app()->getLastErrorMsg(), 'var_dump');
    P(Yaf_Application::app()->getLastErrorNo());

    $request = $this->getRequest();
    $yafRequest = Yaf_Application::app()->getDispatcher()->getRequest();
    var_dump($request instanceof $yafRequest);

    printf("<br>====================================");
    //P($this->_setSession('user',['user'=>'name','age'=>33]),'var_dump');
    P($this->_setSession('user', 'kkkkkkkkkk'), 'var_dump');

    P($this->_hasSession('user'), 'var_dump');

    P($this->_getSession('user'), 'var_dump');
    //
    //P($this->_delSession('user'),'var_dump');
    //
    //P($this->_getSession('user'),'var_dump');

    printf("====================================");


    var_dump($this->getRequest()->getException());

    //throw new Exception('asdfasdfasdf');

    throw new Exception('132456');


    /*

      ini_set('yaf.environ','develop');


      P(getDispatcher()->getRouter());*/

    P(ini_get('yaf.environ'));


    P(Yaf_Application::app()->environ());

    //P(isAjax());

    /*  $route=new Yaf_Route_Rewrite('a',['controller'=>'index','action'=>'vv']);


      var_dump($this->getView());


      P(app()->getConfig());*/

    //P( Yaf_Loader::getInstance());


    //Yaf_Loader::getInstance()->registerLocalNamespace(array(APPLICATION_PATH.'/tuozhan/smarty/sysplugins', "Bar","vv"));
    //P(Yaf_Loader::getInstance()->getLocalNamespace());
    //var_dump($this->getView()->display('/index/index.html'));

    //$this->assign('username','james');
    $this->getView()->assign('username', 'james');


    $this->getView()->display('/index/index.html');


    /* P(TEMPLATE_DIR);

     P(ENVIRONMENT);

     P($this->_get('username'));
     var_dump($route);*/

  }

  /**
   * 默认动作
   * Yaf支持直接把Yaf_Request_Abstract::getParam()得到的同名参数作为Action的形参
   * 对于如下的例子, 当访问http://yourhost/web/index/index/index/name/root 的时候, 你就会发现不同
   */
  public function indexAction($name = "Stranger") {
    //1. fetch query
    $get = $this->getRequest()->getQuery("get", "default value");

    //2. fetch model
    $model = new SampleModel();

    //3. assign
    $this->getView()->assign("content", $model->selectSample());
    $this->getView()->assign("name", $name);

    //4. render by Yaf, 如果这里返回FALSE, Yaf将不会调用自动视图引擎Render模板
    return TRUE;
  }
}
