## Vue14-经验笔记
### Vue获取数据
#### vue结合ajax开发
* 首先在methods中写好获取数据的方法
* 然后使用钩子函数mounted，在mounted中调用写好的获取数据的方法
* 虽然ajax写在vue的methods中，但是不能直接使用this，会指向jquery对象，要使用vue实例名才能指向data区域。

### 动态渲染超链接
* 使用v-bind对超链接进行绑定，重点在于链接的拼接
```
<div id="app">
  <p v-html="msg"></p>
  <a v-bind:href="url+'?id='+uid">点击</a>
</div>
<script>
  var vm = new Vue({
    el: '#app',
    data: {
      msg: '<span>hello</span>',
      url: 'https://www.baidu.com',
      uid: 7
      }
    });
</script>
```