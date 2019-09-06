## tp5-01-新建项目
### 下载
通过git下载tp5项目框架，然后在项目里面新建thinkphp文件夹，并把核心框架下载到里面。
记得在在thinkphp文件夹里面使用git check 5.1 更改版本，否则运行会出错。

### 模块配置
#### 新建
在项目根目录中，运行命令 `php think build --module test` 就能新建test模块。
##### 新建控制器
* 执行下面的指令可以生成index模块的Blog控制器类库文件
  * `>php think make:controller index/Blog`
  * 默认生成的控制器类继承\think\Controller ，并且生成了资源操作方法
* 生成空的控制器则可以使用
  * `>php think make:controller index/Blog --plain`
* 需要生成多级控制器，可以使用
  * `>php think make:controller index/test/Blog --plain`
> v5.1.6+版本开始，可以支持 --api 参数生成用于API接口的资源控制器
##### 新建模型
* 和生成控制器类似，执行下面的指令可以生成index模块的Blog模型类库文件
  * `>php think make:model index/Blog`
* 如果要生成带后缀的类库，可以直接使用
  * `>php think make:controller index/BlogController`
  * `>php think make:model index/BlogModel`
##### 新建中间件
* v5.1.6+版本开始，可以使用下面的指令生成一个中间件类
  * `>php think make:middleware Auth`
  * 会自动生成一个 app\http\middleware\Auth类文件
##### 新建验证器
* 生成一个 app\index\validate\User 验证器类，然后添加自己的验证规则和错误信息
  * `>php think make:validate index/User`
  * 
#### common
common模块是一个特殊的模块，默认是禁止直接访问的，一般用于放置一些公共的类库用于其他模块的继承。

#### 空模块
可以把不存在的模块访问统一指向一个空模块，设置
```
// 设置空模块名为home
'empty_module'	=>	'home',
```

### 环境变量
* 5.1版本取消了所有的系统常量，原来的系统路径变量改为使用Env类获取（需要引入think\facade\Env）

### 控制器
#### 控制器和与URL
* URL请求为`域名/模块/控制器/方法`，当只有模块名时，控制器或者方法缺省时，会直接访问`index`。
* 控制器的方法返回`view()`时，会直接去view中寻找对应的前端页面

### 静态资源

### 响应数据
#### return
* 直接的return只能接受json格式的数据，而且继承自model的类对象，均无法直接使用json进行转换。
