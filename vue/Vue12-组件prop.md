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