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
![Image text](https://images2018.cnblogs.com/blog/1205975/201808/1205975-20180802141042140-162137009.png)