# yaf_frameword_api

why上传yaf框架<br>

1、通过版本控制保存此项目<br>
2、可以分享给大家借鉴 O(∩_∩)O~ <br>

怎么运行<br>
1、安装composer <br>
2、执行composer update 下载需要的扩展  vendor 目录


yaf框架目录调整<br>
1、将application目录改成了app<br>
2、将根目录下conf/application.ini 挪移到 app/configs/application.ini<br>
3、app目录下创建core目录，并创建了BaseController.php BaseModel.php Common.php 公共库<br>
4、项目开启了多模块，默认模块是Index，多模块下的models不是自动引入的，我做了修改，并添加了Services文件夹，service是一个中间层，这样就把Controller、Model彻底分离开，Service也会自动引入；   在app/configs/autoload.php <br>

核心扩展<br>
1、把插件注册方式修改成进行抽离，抽离到配置文件 app/configs/initConfig.php <br>
2、增加日志类，app/core/Log.php 使用方法 log_message('debut','message'); <br>
   日志存储路径在app/configs/config.ini 配置文件中，可以任意修改<br>
   
3、核心函数库扩展  <br>
   getInstance(); 获取当前访问类的控制器
   isAjax()
   isOptions()
   isGet() 等函数

4、自定义修改了yaf异常机制，通过 plugins/CommonException.php 插件进行了注册修改。<br>
   此功能的修改可以在 app/configs/initConfig.php 中进行配置是否开启<br>



还有其他一些目录的调整和优化。

备注：
核心配置文件 app/configs/config.ini 请保留系统配置，可修改配置的value，但不要删除配置项。
<br>你可以增加其他配置项，这个不受影响。


未完待续...
