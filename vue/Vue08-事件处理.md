## Vue08-事件处理
### 监听事件
* v-on
监听 DOM 事件，并在触发时运行一些 JavaScript 代码
### 监听事件
* v-on 可以接收一个需要调用的方法名称
* 简写 @事件，例`@click="test()"`
```
<button v-on:click="greet">Greet</button>
methods: {
  greet: function (event) {
    // `this` 在方法里指向当前 Vue 实例
    alert('Hello ' + this.name + '!')
    // `event` 是原生 DOM 事件
    if (event) {
      alert(event.target.tagName)
    }
  }
}
```
### 内联处理器
* 除了直接绑定到一个方法，也可以在内联 JavaScript 语句中调用方法
```
<button v-on:click="say('hi')">Say hi</button>

methods: {
  say: function (message) {
    alert(message)
  }
}
```
* 有时也需要在内联语句处理器中访问原始的 DOM 事件。可以用特殊变量 $event 把它传入方法
```
<button v-on:click="warn('Form cannot be submitted yet.', $event)">
  Submit
</button>

methods: {
  warn: function (message, event) {
    // 现在我们可以访问原生事件对象
    if (event) event.preventDefault()
    alert(message)
  }
}
```
### 事件修饰符
* 在事件处理程序中调用 event.preventDefault() 或 event.stopPropagation() 是非常常见的需求
* **更好的方式是：方法只有纯粹的数据逻辑，而不是去处理 DOM 事件细节**
* 修饰符是由点开头的指令后缀来表示的。
  * .stop
  * .prevent
  * .capture
  * .self
  * .once
  * .passive
* 例子
```
<!-- 阻止单击事件继续传播 -->
<a v-on:click.stop="doThis"></a>
```
```
<!-- 提交事件不再重载页面 -->
<form v-on:submit.prevent="onSubmit"></form>
```
```
<!-- 修饰符可以串联 -->
<a v-on:click.stop.prevent="doThat"></a>
```
```
<!-- 只有修饰符 -->
<form v-on:submit.prevent></form>
```
```
<!-- 添加事件监听器时使用事件捕获模式 -->
<!-- 即元素自身触发的事件先在此处理，然后才交由内部元素进行处理 -->
<div v-on:click.capture="doThis">...</div>
```
```
<!-- 只当在 event.target 是当前元素自身时触发处理函数 -->
<!-- 即事件不是从内部元素触发的 -->
<div v-on:click.self="doThat">...</div>
```
```
<!-- 点击事件将只会触发一次 -->
<a v-on:click.once="doThis"></a>
```
> .once 修饰符还能被用到自定义的组件事件上
```
<!-- 滚动事件的默认行为 (即滚动行为) 将会立即触发 -->
<!-- 而不会等待 `onScroll` 完成  -->
<!-- 这其中包含 `event.preventDefault()` 的情况 -->
<div v-on:scroll.passive="onScroll">...</div>
```
> 对应 addEventListener 中的 passive 选项提供了 .passive 修饰符
> 尤其能够提升移动端的性能
* **使用修饰符时，顺序很重要；相应的代码会以同样的顺序产生**
  * 用 v-on:click.prevent.self 会阻止所有的点击，而 v-on:click.self.prevent 只会阻止对元素自身的点击。
* **不要把 .passive 和 .prevent 一起使用，因为 .prevent 将会被忽略，同时浏览器可能会向你展示一个警告**
### 按键修饰符
* Vue 允许为 v-on 在监听键盘事件时添加按键修饰符
```
<!-- 只有在 `key` 是 `Enter` 时调用 `vm.submit()` -->
<input v-on:keyup.enter="submit">
```
* 可以直接将 KeyboardEvent.key 暴露的任意有效按键名转换为 kebab-case 来作为修饰符
```
<input v-on:keyup.page-down="onPageDown">
```
* 为了在必要的情况下支持旧浏览器，Vue 提供了绝大多数常用的按键码的别名
  * .enter
  * .tab
  * .delete (捕获“删除”和“退格”键)
  * .esc
  * .space
  * .up
  * .down
  * .left
  * .right
* 可以通过全局 config.keyCodes 对象自定义按键修饰符别名
```
// 可以使用 `v-on:keyup.f1`
Vue.config.keyCodes.f1 = 112
```
### 系统修饰键
* 可以用如下修饰符来实现仅在按下相应按键时才触发鼠标或键盘事件的监听器
	* *.ctrl
	* .alt
	* .shift
	* .meta
* 例子
```
<!-- Alt + C -->
<input @keyup.alt.67="clear">

<!-- Ctrl + Click -->
<div @click.ctrl="doSomething">Do something</div>
```
* 请注意修饰键与常规按键不同
  * 在和 keyup 事件一起用时，事件触发时修饰键必须处于按下状态。
  * 只有在按住 ctrl 的情况下释放其它按键，才能触发 keyup.ctrl。而单单释放 ctrl 也不会触发事件
* .exact
  * 允许你控制由精确的系统修饰符组合触发的事件
```
<!-- 即使 Alt 或 Shift 被一同按下时也会触发 -->
<button @click.ctrl="onClick">A</button>

<!-- 有且只有 Ctrl 被按下的时候才触发 -->
<button @click.ctrl.exact="onCtrlClick">A</button>

<!-- 没有任何系统修饰符被按下的时候才触发 -->
<button @click.exact="onClick">A</button>
```
* 鼠标按钮修饰符
  * .left
  * .right
  * .middle
### 
### 