## Vue组件01-注册组件
### 组件注册
#### 组件名
* `Vue.component` 的第一个参数就是组件名
* 最好使用W3C规范来定义组件名(字母全小写且必须包含一个连字符)
* 风格指南
  * 使用同一前缀
  * 开头大写的驼峰法
  * 只应该拥有单个活跃实例的组件应该以 The 前缀命名，以示其唯一性
  * 和父组件紧密耦合的子组件应该以父组件名作为前缀命名。
  * 组件名应该以高级别的 (通常是一般化描述的) 单词开头，以描述性的修饰词结尾。
  * 对于绝大多数项目来说，在单文件组件和字符串模板中组件名应该总是 PascalCase 的——但是在 DOM 模板中总是 kebab-case 的。
  * 组件名应该倾向于完整单词而不是缩写。
  * 在声明 prop 的时候，其命名应该始终使用 camelCase，而在模板和 JSX 中应该始终使用 kebab-case。
  * 多个特性的元素应该分多行撰写，每个特性一行。

#### 全局注册
* 使用 Vue.component 创建组件
* 注册之后可以用在任何新创建的 Vue 根实例模板中
* 全局注册的组件在所有子组件中均可使用
```
Vue.component('component-a', { /* ... */ })
```

#### 局部注册
* 先定义一个普通的对象表示组件
* 然后在 components 选项中定义你想要使用的组件
* 在 components 中，属性名就是自定义元素名，属性值就是对象
* **局部注册的组件在其子组件中不可用**
```
// 定义对象
var ComponentA = { /* ... */ }
var ComponentB = { /* ... */ }

// 使用对象
new Vue({
  el: '#app',
  components: {
    'component-a': ComponentA,
    'component-b': ComponentB
  }
})

// 如果希望 ComponentA 在 ComponentB 中可用
var ComponentB = {
  components: {
    'component-a': ComponentA
  },
  // ...
}
```