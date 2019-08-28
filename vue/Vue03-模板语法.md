## Vue03-模板语法
### 梗概
* Vue.js 使用了基于 HTML 的模板语法，允许开发者声明式地将 DOM 绑定至底层 Vue 实例的数据
* 所有 Vue.js 的模板都是合法的 HTML ，所以能被遵循规范的浏览器和 HTML 解析器解析
* 在底层的实现上，Vue 将模板编译成虚拟 DOM 渲染函数
* 结合响应系统，Vue 能够智能地计算出最少需要重新渲染多少组件，并把 DOM 操作次数减到最少。
* **也可以不用模板，直接写渲染 (render) 函数，使用可选的 JSX 语法。**

### 插值
#### 文本
* 数据绑定最常见的形式就是使用“Mustache”语法 (双大括号) 的文本插值，双大括号会将数据解释为普通文本
```
<span>Message: {{ msg }}</span>
```
* Mustache 标签将会被替代为对应数据对象上 msg 属性的值。
* 无论何时，绑定的数据对象上 msg 属性发生了改变，插值处的内容都会更新。
* 可以使用v-once执行一次性的插值，但会影响该节点上的其他数据绑定

#### 原始HTML
* 使用v-html会输出真正的html代码
```
<p>Using mustaches: {{ rawHtml }}</p>
<p>Using v-html directive: <span v-html="rawHtml"></span></p>
```
> 这个 span 的内容将会被替换成为属性值 rawHtml，直接作为 HTML 会忽略解析属性值中的数据绑定

* 不能使用 v-html 来复合局部模板
  * 因为 Vue 不是基于字符串的模板引擎
* 对于用户界面 (UI)，组件更适合作为可重用和可组合的基本单位
* 动态渲染的任意 HTML 可能会非常危险，因为它很容易导致 XSS 攻击

#### 特性
* v-bind可以作用在特性上
```
<div v-bind:id="dynamicId"></div>
```
* 对于布尔特性
>如果 isButtonDisabled 的值是 null、undefined 或 false，disabled 特性甚至不会被包含在渲染出来的 `<button>` 元素中。
```
<button v-bind:disabled="isButtonDisabled">Button</button>
```

#### 使用JavaScript表达式
* 对于所有的数据绑定，Vue.js 都提供了完全的 JavaScript 表达式支持
  * `{{ number + 1 }}`
  * `{{ ok ? 'YES' : 'NO' }}`
  * `{{ message.split('').reverse().join('') }}`
  * `<div v-bind:id="'list-' + id"></div>`
* 表达式会在所属 Vue 实例的数据作用域下作为 JavaScript 被解析
  * 有个限制就是，每个绑定都只能包含单个表达式
* 模板表达式都被放在沙盒中，只能访问全局变量的一个白名单，如 Math 和 Date 。你不应该在模板表达式中试图访问用户定义的全局变量。

### 指令
#### 梗概
* 指令 (Directives) 是带有 v- 前缀的特殊特性
* 指令特性的值预期是单个 JavaScript 表达式 (v-for 是例外情况)
* 指令的职责是，当表达式的值改变时，将其产生的连带影响，响应式地作用于 DOM

#### 参数
> 一些指令能够接收一个“参数”，在指令名称之后以冒号表示
* v-bind
  * v-bind 指令可以用于响应式地更新 HTML 特性
    * `<a v-bind:href="url">...</a>`
    * 在这里 href 是参数，告知 v-bind 指令将该元素的 href 特性与表达式 url 的值绑定。
* v-on
  * `<a v-on:click="doSomething">...</a>`
    * 用于监听 DOM 事件
    * 参数是监听的事件名

#### 动态参数
* 可以用方括号括起来的 JavaScript 表达式作为一个指令的参数
  * `<a v-bind:[attributeName]="url"> ... </a>`
  * 这里的 attributeName 会被作为一个 JavaScript 表达式进行动态求值
  * 求得的值将会作为最终的参数来使用
    * 如果你的 Vue 实例有一个 data 属性 attributeName，其值为 "href"，那么这个绑定将等价于 v-bind:href
  * 使用动态参数为一个动态的事件名绑定处理函数
    * `<a v-on:[eventName]="doSomething"> ... </a>`
    * 当 eventName 的值为 "focus" 时，v-on:[eventName] 将等价于 v-on:focus
* 对动态参数的**值**的约束
  * 动态参数预期会求出一个字符串，异常情况下值为 null
    * 这个特殊的 null 值可以被显性地用于移除绑定
  * 任何其它非字符串类型的值都将会触发一个警告
* 对动态参数**表达式**的约束
  * 动态参数表达式有一些语法约束，因为某些字符，例如空格和引号，放在 HTML 特性名里是无效的。
    * 同样，在 DOM 中使用模板时你需要回避大写键名。
  * 变通的办法是使用没有空格或引号的表达式，或用计算属性替代这种复杂表达式。

#### 修饰符(modifier)
* 以半角句号 . 指明的特殊后缀，用于指出一个指令应该以特殊方式绑定
* 例
  * `<form v-on:submit.prevent="onSubmit">...</form>`
  * .prevent 修饰符告诉 v-on 指令对于触发的事件调用 event.preventDefault()

### 缩写
* 为了减少繁琐的拼写，Vue 为 v-bind 和 v-on 这两个最常用的指令，提供了特定简写
  * v-bind
```
<!-- 完整语法 -->
<a v-bind:href="url">...</a>
<!-- 缩写 -->
<a :href="url">...</a>

```
* v-on
```
<!-- 完整语法 -->
<a v-on:click="doSomething">...</a>
<!-- 缩写 -->
<a @click="doSomething">...</a>

```
> : 与 @ 对于特性名来说都是合法字符，在所有支持 Vue 的浏览器都能被正确地解析，它们不会出现在最终渲染的标记中

