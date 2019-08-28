## Vue06-条件渲染
### v-if
* 用于条件性地渲染一块内容
* 这块内容只会在指令的表达式返回 truthy 值的时候被渲染。
```
<h1 v-if="awesome">Vue is awesome!</h1>
```
### v-else
* v-else 元素必须紧跟在带 v-if 或者 v-else-if 的元素的后面，否则它将不会被识别
* v-else-if 也必须紧跟在带 v-if 或者 v-else-if 的元素之后
```
<h1 v-if="awesome">Vue is awesome!</h1>
<h1 v-else>Oh no 😢</h1>
```
### 可复用元素
* Vue 会尽可能高效地渲染元素，通常会复用已有元素而不是从头开始渲染
```
<template v-if="loginType === 'username'">
  <label>Username</label>
  <input placeholder="Enter your username">
</template>
<template v-else>
  <label>Email</label>
  <input placeholder="Enter your email address">
</template>
```
* Vue通过一个Key区别可复用元素
```
<template v-if="loginType === 'username'">
  <label>Username</label>
  <input placeholder="Enter your username" key="username-input">
</template>
<template v-else>
  <label>Email</label>
  <input placeholder="Enter your email address" key="email-input">
</template>
```
### v-show
* 不同的是带有 v-show 的元素始终会被渲染并保留在 DOM 中。
* v-show 只是简单地切换元素的 CSS 属性 display。
* v-show 不支持 `<template>` 元素，也不支持 v-else
```
<h1 v-show="ok">Hello!</h1>
```
### v-if对比v-show
* v-if 是“真正”的条件渲染
  * 它会确保在切换过程中条件块内的事件监听器和子组件适当地被销毁和重建。
* v-if 也是惰性的：
  * 如果在初始渲染时条件为假，则什么也不做，直到条件第一次变为真时，才会开始渲染条件块。
* v-show不管初始条件是什么，元素总是会被渲染，并且只是简单地基于 CSS 进行切换。
* v-if 有更高的切换开销，而 v-show 有更高的初始渲染开销
### v-if与v-for
* 不推荐同时使用 v-if 和 v-for
* 当 v-if 与 v-for 一起使用时，v-for 具有比 v-if 更高的优先级
