## Vue04-计算属性和侦听器
### 计算属性
> 对于任何复杂逻辑，你都应当使用计算属性
#### 基础例子
```
<p>Computer reversed message: "{{reversedMessage}}"</p>

computed: {
  // 计算属性的getter
  reversedMessage: function () {
  // this执行vm实例
  return this.message.split('').reverse().join('');
  }
}
```
#### 特点
* 计算属性需要定义在computed内部
* 直接使用模板语法的文本插值的调用方式就能使用
* 计算属性就是一个可以实现复杂逻辑的表达式
* reversedMessage依赖于message，当message更新时会自动更新reversedMessage

### 计算属性缓存vs方法
#### 问题
> 在上一个例子中，reversedMessage完全可以作为一个method中的方法调用的时候直接用调用方法的写法
```
<p>Reversed message: "{{ reversedMessage() }}"</p>
methods: {
  reversedMessage: function () {
    return this.message.split('').reverse().join('')
  }
}
```
#### 不同点
* 计算属性是基于它们的响应式依赖进行缓存的
* 计算属性只在相关响应式依赖发生改变时它们才会重新求值
* 每当触发重新渲染时，调用方法将总会再次执行函数
* 在上个例子中，只要message 还没有发生改变，多次访问 reversedMessage 计算属性会立即返回之前的计算结果，而不必再次执行函数
  * 响应式依赖指的是vue组件中的属性或者方法
  * Date.now()不是响应式依赖
```
computed: {
  now: function () {
    return Date.now()
  }
}
```
> 希望使用缓存的时候，就使用计算属性不适用缓存的时候，就使用方法

### 计算属性vs侦听属性
* 问题
  * Vue 提供了一种更通用的方式来观察和响应 Vue 实例上的数据变动：侦听属性
  * 当你有一些数据需要随着其它数据变动而变动时，你很容易滥用 watch
* 不同点
  * watch属性需要对每一个变化的值都定义watch，才能监听多个
  * computed属性只需要将想监听的属性放进方法里面形成依赖就能直接以响应的方式进行实时更新
### 计算属性的setter
* 计算属性默认只有getter
```
// getter
get: function () {
    return this.firstName + ' ' + this.lastName
},
```
* 可以自己提供
```
// setter
set: function (newValue) {
  var names = newValue.split(' ')
  this.firstName = names[0]
  this.lastName = names[names.length - 1]
}
```
> 现在再运行 vm.fullName = 'John Doe' 时，setter 会被调用，vm.firstName 和 vm.lastName 也会相应地被更新。
### 监听器
* watch 选项提供了一个更通用的方法，来响应数据的变化
* 当需要在数据变化时执行异步或开销较大的操作时，这个方式是最有用的