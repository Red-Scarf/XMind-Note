## Vue17-自定义指令
### 使用方法
* 定义指令`Vue.directive(指令名, 钩子函数);`，指令名不带`v-`
* 使用指令`<标签 v-for v-model v-指令名>`
* 自定义指令也有作用域，局部定义时，使用**directives**属性
* 自定义钩子函数，就是在调用指令的过程中，特定触发的函数
```
<p v-color="blue">段落</p>

Vue.directive('color', function(el, binding) {
  // el 代表调用指令的元素
  // binding 代表数据对象
    // binding.name 指令名
    // binding.expression 指令后绑定的表达式字符串(原文)
    // binding.value 指令绑定的表达式结果(解析变量/调用函数/运算后的结果)
    
  el.style.background = binding.value;
});
```
```
<p v-bold>段落</p>
directives: {
  bold: function(dom) {
    dom.style.fontWeight = 'bold';
  }
}
```
### 完整形式
* 自定义指令可以放多个钩子函数，即钩子函数对象
* 可以在钩子函数对象中批量定义多个钩子函数，以便在不同的时间点触发
  * bind: 当指令被绑定到元素上时触发
  * inserted: 当元素被追加到Dom树时触发
```
Vue.directive(指令名, {钩子函数对象}); // 对象中可以放多个钩子函数
Vue.directive('hook', {
  // 定义多个钩子函数
  bind: function() {
    console.log('触发bind');
  },
  inserted: function() {
    console.log('触发inserted');
  }
});
```