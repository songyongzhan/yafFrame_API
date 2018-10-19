<?php
/**
 * @name IndexController
 * @author root
 * @desc 默认控制器
 * @see http://www.php.net/manual/en/class.yaf-controller-abstract.php
 */
class IndexController extends BaseController {


  public function vvAction(){


    //var_dump(Tools_Request::getRequest()->getModuleName());

    $this->_name;

    $model = new SampleModel();


    Yaf_Application::app()->getDispatcher()->getRouter();


    P($model->selectSample());




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
    $this->getView()->assign('username','james');
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
