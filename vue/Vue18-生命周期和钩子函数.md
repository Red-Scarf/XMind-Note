##Vue18-生命周期和钩子函数
### 生命周期
#### 概要
* 执行`new Vue({option})`代码
  * beforeCreate
* 初始化配置属性，将传入的配置参数绑定到vm对象上
  * `created`
  * `beforeMount`
* 渲染模板，渲染页面
  * `mounted`
#### Vue持续运行中的生命周期
* 网页完成
  * `beforeUpdate`
* 当数据更新时，需要重新渲染模板
  * `updated`
### 钩子函数
> Vue生命周期中有很多个时间点，Vue有自带的钩子函数会自动在相应时间点上
#### 
* **beforeCreate** 在创建实例之前，在执行`new Vue()`之后，在初始化设置之前
  * 调用beforeCreate是无法访问data和methods属性
* **created** 完成Vue对象的实例化，但还未渲染模板和页面
  * 可以访问data和methods属性
* **beforeMount** 在数据渲染之前调用
  * 调用时网页上只能看到插值表达式
* **mounted** 在渲染完成之后
  * 调用mounted时网页上数据应该渲染好了
* **beforeUpdate** 在数据更新之后，网页更新之前
  * 数据已经是最新的，网页还是旧的
* **updated** 在数据更新后，网页也重新渲染后
  * 数据和网页都是最新的
* 啊