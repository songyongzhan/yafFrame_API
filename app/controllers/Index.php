<?php

/**
 * @name IndexController
 * @author root
 * @desc 默认控制器
 * @see http://www.php.net/manual/en/class.yaf-controller-abstract.php
 */
class IndexController extends BaseController {


  public function detailAction() {


    //url

    //http://k.yt99.com/article/thumb/201809/20/thumb_1015395ba302cb024232ataQm.jpg!94
    //$this->newsModel->geturl("/article/thumb/201809/20/thumb_1015395ba302cb024232ataQm.jpg!94");


    //https://k.yt99.com/6c15947fe2ca9ed96ee79d72cb2a67f9/5be4ead6/article/201809/06/1142265b90a22286ec6oolIJP.gif
    //$this->newsModel->geturl('/fbb982125724a79e8ef9e7f79a23ed95/5be5234d/article/201809/06/1142275b90a223797bbTvEkFv.gif');
    $this->newsModel->geturl('/f658a2c48968c591cc0ced92192ae17b/5be52dfa/meiwen/aaez6kgg46qo0u0rx5pwehwf.m4a');




    P($this->newsModel->getResponseHeaders());

    //P($this->newsModel->getRequestHeaders());
    exit;

    $data = [
      'nav' => 3,
      'title' => '321',
      'bodycontent' => '123123123123123123content',
      'mtime' => time()
    ];

    $result=$this->newsModel->insert($data,'bb');




    $multidata = [

      [
        'nav' => 3,
        'title' => rand(100, 999),
        'bodycontent' => '123123123123123123content',
        'mtime' => time()
      ], [
        'nav' => 3,
        'title' => rand(100, 999),
        'bodycontent' => '123123123123123123content',
        'mtime' => time()
      ], [
        'nav' => 3,
        'title' => rand(100, 999),
        'bodycontent' => '123123123123123123content',
        'mtime' => time()
      ], [
        'nav' => 3,
        'title' => rand(100, 999),
        'bodycontent' => '123123123123123123content',
        'mtime' => time()
      ], [
        'nav' => 3,
        'title' => rand(100, 999),
        'bodycontent' => '123123123123123123content',
        'mtime' => time()
      ], [
        'nav' => 3,
        'title' => rand(100, 999),
        'bodycontent' => '123123123123123123content',
        'mtime' => time()
      ], [
        'nav' => 3,
        'title' => rand(100, 999),
        'bodycontent' => '123123123123123123content',
        'mtime' => time()
      ], [
        'nav' => 3,
        'title' => rand(100, 999),
        'bodycontent' => '123123123123123123content',
        'mtime' => time()
      ], [
        'nav' => 3,
        'title' => rand(100, 999),
        'bodycontent' => '123123123123123123content',
        'mtime' => time()
      ], [
        'nav' => 3,
        'title' => rand(100, 999),
        'bodycontent' => '123123123123123123content',
        'mtime' => time()
      ]

    ];

    //$result=$this->NewsModel->inserMulti($multidata,'bb');




    /* $newdata=['title'=>'songsong'];
     $result=$this->NewsModel->update(3,$newdata,'bb');*/


    //$result=$this->NewsModel->getOne(10,[],'bb');


    //$result=$this->NewsModel->del(44,'bb');
  /*$newModel=$this->NewsModel;
   $result = $newModel->getListPage([
      'id' => [
        'val' => 30,
        'operator' => '>=',
        'condition' => 'and'
      ],
      'title'=>['val'=>'386']
    ], ['*'], 1, 3, '','bb');
    P($result);*/

    $newModel=$this->NewsModel;
    $result = $newModel->getListPage([
      'id' => [
        'val' => 30,
        'operator' => '>=',
        'condition' => 'and'
      ]
    ], ['*'], 1, 3, '','bb');
    P($result);

    //$result=$this->NewsModel->getLastQuery();


   /* P(spl_object_id($newModel));
    P(spl_object_id($this->NewsModel));

    P($this->NewsModel);*/

    /*$sqls=$this->NewsModel->getSqls();

    Pv($sqls);*/


    //P($this->NewsModel->getLasqQuery());

    $result=$this->NewsModel->query('select * from bb where id >? order by id desc limit 3',[30]);
    P($result);


    exit;


    /* $model=new SampleModel();
     $model->selectSample();*/
    $this->SampleModel->selectSample();


    //$arr=['id'=>'iser','name'=>'aa'];
    //$this->_setCookie('vv',$arr);

    //$this->_setCookie('user','james');
    //$this->_delCookie('vv');

    //P($this->_getCookie('vv'));
    //var_dump($this->_getCookie('user'));

    //$this->assign('user', 'james');

    $this->getView()->display('/index/detail.html');
    //echo $this->_render('/index/detail.html');


  }


  public function bbAction() {

    /*   debugMessage('debug 日志');
       logMessage('info','asdfadsfas');
       logMessage('warning','warningwarningwarningwarningwarningwarningwarningwarningwarningwarning');
       logMessage('debug',['name'=>'james','age'=>'33']);*/


    //return json_encode(['name'=>'james','age'=>'33']);

    echo json_encode(['name' => 'james', 'age' => '33']);
    return TRUE;
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
