## Vue19-通信插件
### Vue-Resource 异步通信插件
* 引入vue-resource.js 文件后，会直接在Vue上挂载$http，使用就是vm.$http.get，this.$http.post
* 常用的方法
  * get(url, [config])
  * post(url, [config])
  * jsonp(url, [body], [config])
* 使用的时候要先引入Vue再引入vue-resource
* 使用的时候直接.$http，然后用then方法处理响应的内容
```
vm.$http.get('./response.php?data=vr发起请求').then(function(response) {
  console.dir(response);
});

vm.$http.post('./response.php', {
  // 请求数据
  data: 'vr发起post请求',
}, {
  // 配置项
  emulateJSON: true,
}).then(function(response) {
  console.dir(response);
});

// jsonp必须请求支持jsonp的服务端
// jsonp的本质就是get请求，参数必须放在url中
vm.$http.jsonp('http://www.b.com/crossDomain.php?data=vr发起请求').then(function(response) {
  console.dir(response);
});
```

### Vue-axios 异步通信插件
* 类似vue-resource
* get的使用`axios#get(url[, config])`
```
axios.get('/user?ID=12345')
  .then(function (response) {
    // handle success
    console.log(response);
  })
  
axios.get('/user', {
    // params属性时固定的
    params: {
      ID: 12345
    }
  })
  .then(function (response) {
    console.log(response);
  })
```
* post的使用`axios#post(url[, data[, config]])`
```
axios.post('/user', {
    firstName: 'Fred',
    lastName: 'Flintstone'
  })
  .then(function (response) {
    console.log(response);
  })
```

### 如何跨域
* CORS
  * 在服务器端设置响应头，允许某些资源请求自己，对前端没有影响
* JSONP
  * 参考vue-resource的jsonp
* 代理请求
  * 利用后端脚本没有同源策略的特点进行代理