## Vue15-事件修饰符
### 修饰符
#### stop
* 等同于JavaScript中的event.stopPropagation(),阻止事件冒泡
#### prevent
* 等同于JavaScript中的event.preventDefault()，阻止默认行为。如果事件可以取消，则取消事件
#### capture
* 与事件冒泡的方向相反，事件捕获由外到内。把事件触发模式改为捕获模式
#### self
* 只会触发自己范围内的事件，不包含子元素。只允许事件由元素本身触发
#### once
* 规定事件只触发一次

### 案例
#### stop
* 当多个div嵌套时，如果父子元素(不论中间间隔几层)均有点击事件，那么点击事件会层层冒泡，触发每一层的事件
* 在点击上加上.stop相当于在每个方法中调用了等同于event.stopPropagation()，事件遇到第一个stop便会停止冒泡
```
<div id="outer" @click="outer">
  <div id="middle" @click="middle">
    <button @click="inner">按钮1</button>
    <button @click.stop="inner">按钮2</button>
  </div>
</div>
```
#### capture
* 捕获事件：嵌套两三层父子关系，然后所有都有点击事件，点击子节点，就会触发从外至内  父节点-》子节点的点击事件
* 需要在每个节点上的时间都使用capture，否则该节点会不生效
* 当一系列节点嵌套时，加了capture的节点会先遵循事件捕获，然后不加的再遵循事件冒泡
![Image text](https://images2018.cnblogs.com/blog/1205975/201808/1205975-20180802141042140-162137009.png)
#### self
* 依旧是需要在每个元素上都写self，才能阻止捕获或者冒泡

#### once
* 元素节点加上once后，只会触发第一次点击的事件，多次点击时，虽然该元素节点不会触发，但会遵循捕获或者冒泡的原则触发其他节点

### 按键事件修饰符
#### 键盘修饰符别名
* 每个按键都有自己的编号代码，为了方便记忆，Vue对常用按键取了别名
  * .enter：回车键
  * .tab：制表键
  * .delete：含delete和backspace键
  * .esc：返回键
  * .space: 空格键
  * .up：向上键
  * .down：向下键
  * .left：向左键
  * .right：向右键
```
<div id="outer" @keyup.enter="enter">
  <input type="text">
</div>
```
* 使用时，要使用keyup或者相关的键盘事件，然后.相应的按键名，就可以对不用的按键指定不同的事件
#### 鼠标修饰符
* 和按键修饰符的使用类似
* .left：鼠标左键
* .middle：鼠标中间滚轮
* .right：鼠标右键
#### 其他修饰符
* .ctrl
* .alt
* .shift
* .meta

### 自定义按键修饰符别名
* 在Vue中可以通过config.keyCodes自定义按键修饰符别名
```
<!-- HTML -->
<div id="app">
    <input type="text" v-on:keydown.f5="prompt()">
</div>

Vue.config.keyCodes.f5 = 116;

let app = new Vue({
    el: '#app',
    methods: {
        prompt: function() {
            alert('我是 F5！');
        }
    }
});
```