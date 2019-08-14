## Vue入门10-组件基础
### 定义组件
* 首先是组件的定义，下面例子定义了一个名为 button-counter 的新组件

```
Vue.component('button-counter', {
  data: function () {
    return {
      count: 0
    }
  },
  template: '<button v-on:click="count++">You clicked me {{ count }} times.</button>'
})
```
* 组件是可复用的 Vue 实例，且带有一个名字。使用时只需对想要复用的根元素声明Vue对象即可。
```
<div id="components-demo">
  <button-counter></button-counter>
</div>

new Vue({ el: '#components-demo' })
```
>组件与 new Vue 接收相同的选项，例如 data、computed、watch、methods 以及生命周期钩子等。**仅有的例外是像 el 这样根实例特有的选项。**

### 组件的复用
* 可以将组件进行任意次数的复用
```
<div id="components-demo">
  <button-counter></button-counter>
  <button-counter></button-counter>
  <button-counter></button-counter>
</div>
```
> 每用一次组件，就会有一个它的新实例被创建。

#### data 必须是一个函数
* Vue考虑到，组件虽然复用，但是每个组件实例之间必须独立。所以当我们定义组件时，**data必须返回一个函数**，这样每个实例才能返回一个对象的独立拷贝。

### 组件的组织
* 通常一个应用会以一棵嵌套的组件树的形式来组织
* 为了能在模板中使用，这些组件必须先注册以便 Vue 能够识别。
* 这里有两种组件的注册类型：**全局注册和局部注册。**

全局注册
```
Vue.component('my-component-name', {
  // ... options ...
})
```
>全局注册的组件可以用在其被注册之后的任何 (通过 new Vue) 新创建的 Vue 根实例，也包括其组件树中的所有子组件的模板中。

### 通过 Prop 向子组件传递数据
* 默认情况下，组件没办法接收外部的变量，需要接收外界变量时，要使用props属性。
* props属性是一个数组，只有在props中声明的变量名才能被组件实例所接受。
* 一个组件默认可以拥有任意数量的 prop，任何值都可以传递给任何 prop。
* 我们能够在组件实例中访问这个值，就像访问 data 中的值一样。
```
Vue.component('blog-post', {
  props: ['title'],
  template: '<h3>{{ title }}</h3>'
})
```
>当使用上述代码时，直接使用title属性即可注入到组件中
```
<blog-post title="My journey with Vue"></blog-post>
<blog-post title="Blogging with Vue"></blog-post>
<blog-post title="Why Vue is so fun"></blog-post>
```
>需要使用数组或者其他变量注入到组件中时，可以使用绑定的方式
```
var vm = new Vue({
	el: '#component-1',
	data: {
		posts: [
			{id: 1, title: 'My journey with Vue'},
			{id: 2, title: 'Blogging with Vue'},
			{id: 3, title: 'Why Vue is so fun'},
		]
	}
});
```
```
<blog-post v-for="post in posts" v-bind:key="post.id" v-bind:title="post.title"></blog-post>
```

### 单个根元素
* 当构建一个组件时，组件会含有多个元素
* Vue不予许组件中有多个根元素，只允许有一个根元素
* 即最顶层只能有一个元素
```
<div class="blog-post">
  <h3>{{ title }}</h3>
  <div v-html="content"></div>
</div>
```
* 看起来当组件变得越来越复杂的时候，为每个相关的信息定义一个 prop 会变得很麻烦。所以重构这个组件，让它变成接受一个单独的prop。
* 因为引入的是一个对象，所以不论对象属性如何改变，组件内部都能直接使用。
```
<blog-post
  v-for="post in posts"
  v-bind:key="post.id"
  v-bind:post="post"
></blog-post>
```
```
Vue.component('blog-post', {
  props: ['post'],
  template: `
    <div class="blog-post">
      <h3>{{ post.title }}</h3>
      <div v-html="post.content"></div>
    </div>
  `
})
```
>上述的这个和一些接下来的示例使用了 JavaScript 的模板字符串来让多行的模板更易读。它们在 IE 下并没有被支持，所以如果你需要在不 (经过 Babel 或 TypeScript 之类的工具) 编译的情况下支持 IE，**请使用折行转义字符(\\)取而代之。**

### 监听子组件事件
* 在我们开发组件时，它的一些功能可能要求我们和父级组件进行沟通。
* 在其父组件中，我们可以通过添加一个 postFontSize 数据属性来支持这个功能。
具体详情请看[监听子组件事件](https://cn.vuejs.org/v2/guide/components.html#监听子组件事件 "监听子组件事件")
>组件的template中，**$emit内注册的事件不区分大小写**，而正文中声明组件的v-on事件绑定时是区分大小写的，所以最好全部小写。

#### 使用事件抛出一个值
* 有时子组件需要使用事件抛出一个值，如设置文本要放大多少
* 这时可以使用$emit的第二个参数提供这个值
```
<button v-on:click="$emit('enlarge-text', 0.1)">
  Enlarge text
</button>
```
* 用$event接收这个值
```
<div :style="{fontSize: postFontSize + 'em'}">
    <blog-posts 
    v-for="post in posts" 
    v-bind:key="post.id" 
    v-bind:post="post" 
    v-on:add-fontsize="postFontSize += $event" 
    v-on:sub-fontsize="postFontSize -= $event"
    ></blog-posts>
</div>
```
* 如果这个事件处理函数是一个方法，这个值将会作为第一个参数传入这个方法
```
<blog-post
  ...
  v-on:enlarge-text="onEnlargeText"
></blog-post>

methods: {
  onEnlargeText: function (enlargeAmount) {
    this.postFontSize += enlargeAmount
  }
}
```

#### 在组件上使用v-model
* 在input中是 `<input v-model="searchText">` 
  * 等价于 `<input v-bind:value="searchText" v-on:input="searchText = $event.target.value">`
* 使用在组件上时`<custom-input v-model="searchText"></custom-input>` 
  * 等价于 `<custom-input v-bind:value="searchText" v-on:input="searchText = $event"></custom-input>`
* 为了让input标签正常工作，这个组件内的必须
  * 将其**value**特性绑定到一个名叫*value*的**prop**上
  * 在其**input**事件被触发时，将新的值通过自定义的**input**事件抛出
```
Vue.component('custom-input', {
  props: ['value'],
  template: `
    <input
      v-bind:value="value"
      v-on:input="$emit('input', $event.target.value)"
    >
  `
})
```

### 通过插槽分发内容
* Vue的`<slot>`元素就是一个插槽，可以将使用标签时的内容插到插槽的位置
```
Vue.component('alert-box', {
  template: `
    <div class="demo-alert-box">
      <strong>Error!</strong>
      <slot></slot>
    </div>
  `
})

<alert-box>
  Something bad happened.
</alert-box>
```

### 动态组件
* 通过动态组件可以实现动态加载或者切换组件，比如多标签页面
* 通过 Vue 的 `<component>` 元素加一个特殊的 `is` 特性来实现
* 下例中的 `currentTabComponent` 可以包括
  * 已注册组件的名字
  * 一个组件的选项对象
* 实现流程
  * 先定义好各个组件
  * 定义一个计算属性(本例中是为了拼接字符串)
  * 循环tabs生成按钮列，绑定点击事件
```
data: {
	currentTab: 'Home',
	tabs: ['Home', 'Posts', 'Archive']
},
computed: {
	currentTabComponent: function () {
		return 'tab-' + this.currentTab.toLowerCase();
	}
}

<button
	v-for="tab in tabs"
	v-bind:key="tab"
	v-bind:class="['tab-button', { active: currentTab === tab }]"
	v-on:click="currentTab = tab"
>{{tab}}</button>
<component
	v-bind:is="currentTabComponent"
	class="tab"
></component>
```

#### 解析 DOM 模板时的注意事项
* 部分特定元素对于内部的元素是有一定限制的，比如 `<selection>` 内只能有 `<option>` 元素
* 这就导致组件模板元素符合条件，但组件名称不符合的时候，渲染失败
* 可以使用is属性绕过这个限制
```
<-- 这样不合法 -->
<table>
  <blog-post-row></blog-post-row>
</table>

<-- 这样合法 -->
<table>
  <tr is="blog-post-row"></tr>
</table>
```