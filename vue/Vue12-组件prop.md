## Vue12-组件prop
### Prop 的大小写 (camelCase vs kebab-case)
* HTML 中的特性名是大小写不敏感的，所以浏览器会把所有大写字符解释为小写字符
* 使用 DOM 中的模板时，camelCase (驼峰命名法) 的 prop 名需要使用其等价的 kebab-case (短横线分隔命名) 命名
* **如果使用字符串模板，那么这个限制就不存在了**
```
Vue.component('blog-post', {
  // 在 JavaScript 中是 camelCase 的
  props: ['postTitle'],
  template: '<h3>{{ postTitle }}</h3>'
})

<!-- 在 HTML 中是 kebab-case 的 -->
<blog-post post-title="hello!"></blog-post>
```
### prop类型
* 常见的是使用数组
* 如果要指定每个peop的值类型，可以使用对象形式，属性名就是prop名，属性值就是prop的值
```
props: ['title', 'likes', 'isPublished', 'commentIds', 'author']

props: {
  title: String,
  likes: Number,
  isPublished: Boolean,
  commentIds: Array,
  author: Object,
  callback: Function,
  contactsPromise: Promise // or any other constructor
}
```
### 传递静态或动态prop
* 组件配置props属性后，可以直接在标签上使用该属性传值
* 也可以使用v-bind进行动态绑定的传值
```
<blog-post title="My journey with Vue"></blog-post>

<!-- 动态赋予一个变量的值 -->
<blog-post v-bind:title="post.title"></blog-post>
```
* 传值的对象不限于字符串，还包括**数字、布尔值、数组、对象、对象的所有属性**
  * 数字 
  ```
  <blog-post v-bind:likes="42"></blog-post>
  <!-- 用一个变量进行动态赋值。-->
  <blog-post v-bind:likes="post.likes"></blog-post>
  ```
  * 布尔值
  ```
  <!-- 包含该 prop 没有值的情况在内，都意味着 `true`。-->
  <blog-post is-published></blog-post>
  <!-- 即便 `false` 是静态的，我们仍然需要 `v-bind` 来告诉 Vue -->
  <blog-post v-bind:is-published="false"></blog-post>
  <!-- 用一个变量进行动态赋值。-->
  <blog-post v-bind:is-published="post.isPublished"></blog-post>
  ```
  * 数组
  ```
  <!-- 即便数组是静态的，我们仍然需要 `v-bind` 来告诉 Vue -->
  <blog-post v-bind:comment-ids="[234, 266, 273]"></blog-post>
  <!-- 用一个变量进行动态赋值。-->
  <blog-post v-bind:comment-ids="post.commentIds"></blog-post>
  ```
  * 对象
  ```
  <blog-post
    v-bind:author="{
      name: 'Veronica',
      company: 'Veridian Dynamics'
    }"
  ></blog-post>
  <!-- 用一个变量进行动态赋值。-->
  <blog-post v-bind:author="post.author"></blog-post>
  ```
  * 对象的所有属性
    * 如果你想要将一个对象的所有属性都作为 prop 传入，你可以使用不带参数的 v-bind (取代 v-bind:prop-name)
  ```
  <blog-post v-bind="post"></blog-post>
  ```
### 单向数据流
* 所有的 prop 都使得其父子 prop 之间形成了一个单向下行绑定
  * 父级 prop 的更新会向下流动到子组件中，但是反过来则不行
  * 防止从子组件意外改变父级组件的状态，从而导致你的应用的数据流向难以理解
* 每次父级组件发生更新时，子组件中所有的 prop 都将会刷新为最新的值
  * 不应该在一个子组件内部改变 prop。如果你这样做了，Vue 会在浏览器的控制台中发出警告
* 普通用法
```
Vue.component('props-test', {
	props: ['initial'],
	data: function () {
		return {
			counter: this.initial
		};
	},
	template: '<p>{{counter}}</p>'
});
var vm1 = new Vue({
	el: '#vm1',
	data: {
		init: 'dsjh'
	}
});

<props-test v-bind:initial="init"></props-test>
```
>数据的流动：vue实例中的init变量->html中的v-bind:initial->prop的initial->data的return的counter
* 过滤或转换的用法
```
props: ['size'],
computed: {
  normalizedSize: function () {
    return this.size.trim().toLowerCase()
  }
}
```
>**注意！JS中对象和数组引用传递，所以组件改变对象或数组会影响父组件的情况**

## prop验证
### 常规验证
* 可以对prop中的值进行验证，不符合条件的可以进行警告
* 为了定制prop的验证方式，你可以为props中的值提供一个带有验证需求的对象，而不是一个字符串数组
>注意那些 prop 会在一个组件实例创建之前进行验证，所以实例的属性 (如 data、computed 等) 在 default 或 validator 函数中是不可用的
```
props: {
			// 单个基本数据类型(`null` 和 `undefined` 会通过任何类型验证)
			propA: Number,
			// 多个可能类型
			propB: [String, Number],
			// 必填
			propC: {
				type: String,
				requires: true
			},
			// 带有默认值
			propD: {
				type: Number,
				default: 100
			},
			// 带有默认值的对象
			propE: {
				type: Object,
				// 对象或数组默认值必须返回一个工厂函数获取
				default: function () {
					return {message: 'hello'};
				}
			},
			// 自定义验证函数
			propF: {
				validator: function (value) {
					// value必须是下列中的一个
					return ['success', 'warning', 'danger'].indexOf(value) !== -1;
				}
			}
		},
```
### type类型检查
* 验证规则中，有一个很重要的属性type，可以是下列原生构造函数中的一个
  * String
  * Number
  * Boolean
  * Array
  * Object
  * Date
  * Function
  * Symbol
* type 还可以是一个自定义的构造函数，并且通过 instanceof 来进行检查确认
```
// 给定构造函数
function Person (firstName, lastName) {
  this.firstName = firstName
  this.lastName = lastName
}
// 验证 author prop 的值是否是通过 new Person 创建的
Vue.component('blog-post', {
  props: {
    author: Person
  }
})
```

## 非 Prop 的特性
* 一个非 prop 特性是指传向一个组件，但是该组件并没有相应 prop 定义的特性
* 显式定义的 prop 适用于向一个子组件传入信息，然而组件库的作者并不总能预见组件会被用于怎样的场景
* 使用第三方组件时，会将外部调用时的属性自动写入到组件的根节点中
```
// 自动添加到组件的根元素上
<bootstrap-date-input data-date-picker="activated"></bootstrap-date-input>
```
### 替换/合并已有的特性
* 外部写了class或style时，会自动与组件根节点的class或style合并
### 禁用特性继承
* 不想组件的根元素继承特性，可以在组件的选项中设置 inheritAttrs: false
* inheritAttrs: false 选项不会影响 style 和 class 的绑定
```
Vue.component('my-component', {
  inheritAttrs: false,
  // ...
})
```