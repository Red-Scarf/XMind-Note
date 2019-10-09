## Vue01-基础学习
### 安装使用
#### 直接引用
```
<!-- 开发环境版本，包含了有帮助的命令行警告 -->
<script src="https://cdn.jsdelivr.net/npm/vue/dist/vue.js"></script>
<!-- 生产环境版本，优化了尺寸和速度 -->
<script src="https://cdn.jsdelivr.net/npm/vue"></script>
```
对于制作原型或学习，使用最新版本
```
<script src="https://cdn.jsdelivr.net/npm/vue"></script>
```
#### NPM
* 用 Vue 构建大型应用时推荐使用 NPM 安装
* NPM 能很好地和诸如 webpack 或 Browserify 模块打包器配合使用。
* 同时 Vue 也提供配套工具来开发单文件组件。
#### CLI
* Vue 提供了一个官方的 CLI，为单页面应用 (SPA) 快速搭建繁杂的脚手架。
* 只需要几分钟的时间就可以运行起来并带有热重载、保存时 lint 校验，以及生产环境可用的构建版本。

### 声明式渲染
允许采用简洁的模板语法来声明式地将数据渲染进 DOM 

#### 文本插值
```
<div id="app">
  {{ message }}
</div>

var app = new Vue({
  el: '#app',
  data: {
    message: 'Hello Vue!'
  }
})
```
#### v-bind
* 可以绑定表达式，包括字符串拼接、逻辑表达式、运算表达式。
* 简写，:属性，例`:title="aaa"`
```
<div id="app-2">
  <span v-bind:title="message">
    鼠标悬停几秒钟查看此处动态绑定的提示信息！
  </span>
</div>
```
```
var app2 = new Vue({
  el: '#app-2',
  data: {
    message: '页面加载于 ' + new Date().toLocaleString()
  }
})
```
> 将这个元素节点的 title 特性和 Vue 实例的 message 属性一直保持一致

### 条件、循环
#### v-if
```
<div id="app-3">
  <p v-if="seen">现在你看到我了</p>
</div>

var app3 = new Vue({
  el: '#app-3',
  data: {
    seen: true
  }
})
```
> 不仅可以把数据绑定到 DOM 文本或特性，还可以绑定到 DOM 结构
#### v-for
* 绑定数组的数据来渲染一个项目列表
```
<li v-for="todo in todos">
  {{ todo.text }}
</li>

data: {
  todos: [
    { text: '学习 JavaScript' },
    { text: '学习 Vue' },
    { text: '整个牛项目' }
  ]
}
```
* push()
对array加一
```
app4.todos.push({ text: '新项目' })
```

### 处理用户输入
#### v-on
* 添加一个事件监听器
```
<button v-on:click="reverseMessage">反转消息</button>
```
```
data: {
  message: 'Hello Vue.js!'
},
methods: {
  reverseMessage: function () {
    this.message = this.message.split('').reverse().join('')
  }
}
```

* reverseMessage 方法中，只更新了应用的状态，没有触碰 DOM
所有的 DOM 操作都由 Vue 来处理
#### v-model
* 实现表单输入和应用状态之间的双向绑定
```
<p>{{ message }}</p>
<input v-model="message">
data: {
  message: 'Hello Vue!'
}
```

### 组件
* 是一种抽象，允许我们使用小型、独立和通常可复用的组件构建大型应用
* 在 Vue 里，一个组件本质上是一个拥有预定义选项的一个 Vue 实例
#### 注册组件
```
// 定义名为 todo-item 的新组件
Vue.component('todo-item', {
  template: '<li>这是个待办项</li>'
})
```
#### 构建组件模板
```
<ol>
  <!-- 创建一个 todo-item 组件的实例 -->
  <todo-item></todo-item>
</ol>
```
> Vue 组件非常类似于自定义元素
