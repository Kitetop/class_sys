# kite
> 一个轻量级的、个人的 PHP 框架

````
1. 此框架今后要能够配合composer使用，kite目录作为该框架的核心函数库以及类库

2.app/Kernel是上一层级的核心目录，它负责对kite目录下的函数库类库的引用

3.kite目录下的顶层命名空间定义为 Kite

````

> 此框架的路由规则

````
1. /news => 指向Action目录下的news类

2. /user/news/:id => 指向Action目录下的User目录下的news类，并且传递了一个id的值

3. 对于路由传递的参数还是需要添加一个新的层次，需要对POST、GET传递过来的数据进行接收以及处理

tip：此框架的自定义路由前两字段指向目录结构，后面的代表的是传递数据，即所支持的路由的形式为：
     /news;
     /users/news;
     /users/news/id/1;
     /users/news/username/kitetop/password/1363215999@qq.com => 其中username=kitetop,password=1363215999@qq.com

````
> Action 层级原理

````
1. 最顶层在 /kite/Action/Action中，其他的自定义的action都是继承此action，在此action中定义了doPost、
   doGet、doDelete等方法（与HTTP请求中的方法一致），在自定义的路由的时候只要对设定的请求的方法在对应的
   action子类重写方法即可。
 
2. 在Action层可以使用Service(string $name)方法取得所要调用Service层的对象实例,在Service层可以使用call(string $name, array $params)
   实现Service的组合使用，call方法会直接执行此服务
   
3. 此层主要负责对HTTP过来的数据进行处理，并且将处理后的数据传递给Service层
   
````
> Service 层级原理

````
1. 最顶层在 /kite/Service/Service中，其他自定义的都是继承此Service，子类只需要重写 protect function execute() 即可

2. 此层主要负责对数据进行逻辑处理以及负责将数据存入（删除）的业务逻辑操作
````