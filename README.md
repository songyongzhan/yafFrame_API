# yaf_frameword_api

why上传yaf框架
1、通过版本控制
2、分享给大家借鉴

怎么运行
1、安装composer
2、执行 composer update 下载需要的扩展  vendor 目录


yaf框架目录调整
1、将application目录改成了app
2、将根目录下conf/application.ini 挪移到 app/configs/application.ini
3、app目录下创建core目录，并创建了BaseController.php BaseModel.php Common.php 公共库
4、项目开启了多模块，默认模块是Index，多模块下的models不是自动引入的，我做了修改，并添加了Services文件夹，service是一个中间层，这样就把Controller、Model彻底分离开，Service也会自动引入；   在app/configs/autoload.php 

未完待续...
