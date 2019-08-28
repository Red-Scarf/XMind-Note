## Vue05-Class与Style绑定
### 梗概
* 操作元素的 class 列表和内联样式是数据绑定的一个常见需求
* 因为它们都是属性，所以我们可以用 v-bind 处理它们，只需要通过表达式计算出字符串结果即可
* 因为字符串拼接易错，Vue做了增强
  * 表达式结果除了可以是字符串，还可以是对象或数组
### 绑定Class
> v-bind:class
#### 对象语法
* 可以给v-bind:class传递一个对象，用来动态切换class
* 表示 active 这个 class 存在与否将取决于数据属性 isActive 的 truthiness
```
<div v-bind:class="{ active: isActive }"></div>
```
* 可以在对象中传入更多属性来动态切换多个 class
* v-bind:class 指令也可以与普通的 class 属性共存
```
<div
  class="static"
  v-bind:class="{ active: isActive, 'text-danger': hasError }"
></div>
```
* 结果
```
<div class="static active"></div>
```
* 绑定的数据对象不必内联定义在模板里
```
<div v-bind:class="classObject"></div>
		
data: {
  classObject: {
    active: true,
    'text-danger': false
  }
}
```
#### 注意
> 当Vue使用class这类可以匹配多个元素的选择器时只会获取第一个元素
#### 数组语法
* 可以把一个数组传给 v-bind:class以应用一个 class 列表
```
<div v-bind:class="[activeClass, errorClass]"></div>
data: {
  activeClass: 'active',
  errorClass: 'text-danger'
}
```
渲染为：
```
<div class="active text-danger"></div>
```
* 根据条件切换列表中的 class，可以用三元表达式
```
<div v-bind:class="[isActive ? activeClass : '', errorClass]"></div>
```
#### 用在组件上
* 原理
  * 当在一个自定义组件上使用 class 属性时，这些类将被添加到该组件的根元素上面。这个元素上已经存在的类不会被覆盖。
* 实例
* 声明组件
```
Vue.component('my-component', {
  template: '<p class="foo bar">Hi</p>'
})
```
* 使用的时候
```
<my-component class="baz boo"></my-component>
```
* 渲染
```
<p class="foo bar baz boo">Hi</p>
```
* 带数据绑定class
```
<my-component v-bind:class="{ active: isActive }"></my-component>
```
### 绑定内联样式
> v-bind:style
#### 对象语法
* v-bind:style
  * 看着非常像 CSS，但其实是一个 JavaScript 对象
```
<div v-bind:style="{ color: activeColor, fontSize: fontSize + 'px' }"></div>
data: {
  activeColor: 'red',
  fontSize: 30
}
```
* 直接绑定到一个样式对象通常更好
```
<div v-bind:style="styleObject"></div>
data: {
  styleObject: {
    color: 'red',
    fontSize: '13px'
  }
}
```
> 对象语法常常结合返回对象的计算属性使用
#### 数组语法
* 可以将多个样式对象应用到同一个元素上
```
<div v-bind:style="[baseStyles, overridingStyles]"></div>
```
#### 自动添加前缀
* 当浏览器需要专属前缀时，会自动添加
#### 多重值
* 从 2.3.0 起，你可以为 style 绑定中的属性提供一个包含多个值的数组，**常用于提供多个带前缀的值**
```
<div :style="{ display: ['-webkit-box', '-ms-flexbox', 'flex'] }"></div>
```
> 这样写只会渲染数组中最后一个被浏览器支持的值