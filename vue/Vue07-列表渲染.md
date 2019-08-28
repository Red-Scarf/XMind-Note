## Vue07-列表渲染
### 使用v-for
#### 数组
* v-for 指令需要使用 item in items 形式的特殊语法，其中 items 是源数据数组，而 item 则是被迭代的数组元素的别名
```
<ul id="example-1">
  <li v-for="item in items">
    {{ item.message }}
  </li>
</ul>
data: {
    items: [
      { message: 'Foo' },
      { message: 'Bar' }
    ]
  }
```
* v-for 块中，我们可以访问所有父作用域的属性
* v-for 还支持一个可选的第二个参数，即当前项的索引
```
<li v-for="(item, index) in items">
    {{ parentMessage }} - {{ index }} - {{ item.message }}
</li>
```
* 也可以用 of 替代 in 作为分隔符
```
<div v-for="item of items"></div>
```
#### 对象
* 可以用 v-for 来遍历一个对象的属性
```
<li v-for="value in object">
```
* 你也可以提供第二个的参数为 property 名称 (也就是键名)
```
<div v-for="(value, name) in object">
```
* 还可以用第三个参数作为索引
```
<div v-for="(value, name, index) in object">
```
> 在遍历对象时，会按 Object.keys() 的结果遍历，
但是不能保证它的结果在不同的 JavaScript 引擎下都一致。
### 维护状态
* 当 Vue 正在更新使用 v-for 渲染的元素列表时，它默认使用“就地更新”的策略
* 如果数据项的顺序被改变，Vue 将不会移动 DOM 元素来匹配数据项的顺序，而是就地更新每个元素，并且确保它们在每个索引位置正确渲染。
* 这个默认的模式是高效的，但是只适用于不依赖子组件状态或临时 DOM 状态 
  * (例如：表单输入值) 的列表渲染输出。
* **尽可能在使用 v-for 时提供 key attribute，除非遍历输出的 DOM 内容非常简单，或者是刻意依赖默认行为以获取性能上的提升。**
* 为了给 Vue 一个提示，以便它能跟踪每个节点的身份，从而重用和重新排序现有元素，你需要为每项提供一个唯一 key 属性
```
<div v-for="item in items" v-bind:key="item.id">
  <!-- 内容 -->
</div>
```
### 数组更新检测
> Vue 将被侦听的数组的变异方法进行了包裹，所以它们也将会触发视图更新
#### 变异方法
* push()
* pop()
* shift()
* unshift()
* splice()
* sort()
* reverse()
* 具体看API
#### 替换数组
* 也有非变异 (non-mutating method) 方法，它们不会改变原始数组，而总是返回一个新数组。
* 例如 filter()、concat() 和 slice() 。
#### 注意
* 由于 JavaScript 的限制，Vue 不能检测以下数组的变动
  * 当你利用索引直接设置一个数组项时，例如：vm.items[indexOfItem] = newValue
  * 当你修改数组的长度时，例如：vm.items.length = newLength
* 使用一下方法可以实现相同功能同时触发状态更新
  * Vue.set(vm.items, indexOfItem, newValue)
  * vm.items.splice(indexOfItem, 1, newValue)
  * vm.$set(vm.items, indexOfItem, newValue)
  * vm.items.splice(newLength)
### 对象变更检测
* 由于 JavaScript 的限制，Vue 不能检测对象属性的添加或删除
* 对于已经创建的实例，Vue 不允许动态添加根级别的响应式属性
* 可以使用 Vue.set(object, propertyName, value) 方法向嵌套对象添加响应式属性
  * 添加一个新的 age 属性到嵌套的 userProfile 对象
Vue.set(vm.userProfile, 'age', 27)
```
var vm = new Vue({
  data: {
    userProfile: {
      name: 'Anika'
    }
  }
})
```
### 显示过滤/排序后的结果
* 要显示一个数组经过过滤或排序后的版本，而不实际改变或重置原始数据
```
<li v-for="n in evenNumbers">{{ n }}</li>

evenNumbers: function () {
    return this.numbers.filter(function (number) {
      return number % 2 === 0
    })
  }
```
* 在计算属性不适用的情况下可以使用方法一样可以动态触发视图更新
```
<li v-for="n in even(numbers)">{{ n }}</li>

methods: {
  even: function (numbers) {
    return numbers.filter(function (number) {
      return number % 2 === 0
    })
  }
}
```
### 在`<template>`上使用for
```
<ul>
  <template v-for="item in items">
    <li>{{ item.msg }}</li>
    <li class="divider" role="presentation"></li>
  </template>
</ul>
```
### for和if的使用
* 不建议一起使用
* 当一起使用时，for有较高的优先级
### 组件上使用for
* 2.2.0+ 的版本里，当在组件上使用 v-for 时，key 现在是必须的。
```
<my-component v-for="item in items" :key="item.id"></my-component>
```
* 任何数据都不会被自动传递到组件里，因为组件有自己独立的作用域。为了把迭代数据传递到组件里，我们要使用 prop
```
<div id="vm-1">
  <my-component
    v-for="(item, index) in items"
    v-bind:item="item"
    v-bind:index="index"
    v-bind:key="item.id"
  ></my-component>
</div>
  
Vue.component('my-component', {
  props: ['item', 'index'],
  template: '<li>{{index}}: {{item}}</li>'
});
  
var vm1 = new Vue({
  el: '#vm-1',
  data: {
    items: [31, 3213, 453, 543, 8979, 674, 364]
  }
});
```