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

### 模型
#### 创建模型
* 模型会自动对应数据表，模型类的命名规则是除去表前缀的数据表名称，采用驼峰法命名，并且首字母大写
* 模型自动对应的数据表名称都是遵循小写+下划线规范，如果你的表名有大写的情况，必须通过设置模型的table属性。
* 大多数情况下，不同模块的模型是不需要独立的，因此可以统一在common模块下面定义模型。
* 初始化模型时需要对表字段信息和字段类型进行初始化
```
class Staff extends model {
//在子类重写父类的初始化方法initialize()
  protected function initialize(){
    //继承父类中的initialize()
    parent::initialize();
    //初始化数据表名称，通常自动获取不需设置
    $this->table = 'tp5_staff';
    //初始化数据表字段信息
    $this->field = $this->getTableFields();
    //初始化数据表字段类型
    $this->type = $this->getFieldsType();
    //初始化数据表主键
    $this->pk = $this->db()->getTableInfo('', 'pk');
  }
}
```
#### 新增数据
##### 一条数据
* 插入数据的方法有很多
```
$user           = new User;
$user->name     = 'thinkphp';
$user->email    = 'thinkphp@qq.com';
$user->save();
============
$user = new User;
$user->save([
    'name'  =>  'thinkphp',
    'email' =>  'thinkphp@qq.com'
]);
============
$user = new User([
    'name'  =>  'thinkphp',
    'email' =>  'thinkphp@qq.com'
]);
$user->save();
```
* 如果需要过滤非数据表字段的数据，可以使用
```
$user = new User;
// 过滤post数组中的非数据表字段数据
$user->allowField(true)->save($_POST);
```
* 希望指定某些字段写入
```
$user = new User;
// post数组中只有name和email字段会写入
$user->allowField(['name','email'])->save($_POST);
```
* 最佳的建议是模型数据赋值之前就进行数据过滤
```
$user = new User;
// 过滤post数组中的非数据表字段数据
$data = Request::only(['name','email']);
$user->save($data);
```
* 如果要获取新增数据的自增ID，可以使用下面的方式
```
$user           = new User;
$user->name     = 'thinkphp';
$user->email    = 'thinkphp@qq.com';
$user->save();
// 获取自增ID
echo $user->id;
```
> 这里其实是获取模型的主键，如果你的主键不是id，而是user_id的话，其实获取自增ID就变成这样`$user->user_id;`
##### 多条数据
* 支持批量新增，可以使用**二维数组**
  * saveAll方法新增数据返回的是包含新增模型（带自增ID）的数据集对象。
  * saveAll方法新增数据默认会自动识别数据是需要新增还是更新操作，当数据中存在主键的时候会认为是更新操作
```
$user = new User;
$list = [
    ['name'=>'thinkphp','email'=>'thinkphp@qq.com'],
    ['name'=>'onethink','email'=>'onethink@qq.com']
];
$user->saveAll($list);
```
* 如果需要带主键数据批量新增
```
$user = new User;
$list = [
    ['id'=>1, 'name'=>'thinkphp', 'email'=>'thinkphp@qq.com'],
    ['id'=>2, 'name'=>'onethink', 'email'=>'onethink@qq.com'],
];
$user->saveAll($list, false);
```
* 静态方法
  * 和save方法不同的是，create方法返回的是当前模型的对象实例。
```
$user = User::create([
    'name'  =>  'thinkphp',
    'email' =>  'thinkphp@qq.com'
]);
echo $user->name;
echo $user->email;
echo $user->id; // 获取自增ID
```
* create方法的第二个参数可以传入允许写入的字段列表（传入true则表示仅允许写入数据表定义的字段数据）
```
// 只允许写入name和email字段的数据
$user = User::create([
    'name'  =>  'thinkphp',
    'email' =>  'thinkphp@qq.com'
], ['name', 'email']);
echo $user->name;
echo $user->email;
echo $user->id; // 获取自增ID
```
> **新增数据的最佳实践原则：使用create方法新增数据，使用saveAll批量新增数据。**

#### 更新数据
> **在取出数据后，更改字段内容后使用save方法更新数据。这种方式是最佳的更新方式。**
#### 模型关联
##### 创建关联
* 首先要确定模型间的关系，确定主键和外键的字段
* 创建模型关联，只需要在模型中写好方法即可
```
// person和sex模型，person中有person_sex字段
// 对应的是sex中的sex_no字段，要获取的是sex_name
// 所以需要在person模型中写方法
public function sex() {
  return $this->hasOne('Sex', 'sex_no', 'person_sex');
}
```


### 静态资源

### 响应数据
#### return
* 直接的return只能接受json格式的数据，而且继承自model的类对象，均无法直接使用json进行转换。
