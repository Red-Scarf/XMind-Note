## Vue13-自定义事件
### 事件名
* 事件名不存在任何自动化的大小写转换，触发的事件名需要完全匹配监听这个事件所用的名称
* 不同于组件和 prop，事件名不会被用作一个 JavaScript 变量名或属性名，所以就没有理由使用 camelCase 或 PascalCase 了
* v-on 事件监听器在 DOM 模板中会被自动转换为全小写 (因为 HTML 是大小写不敏感的)，所以 v-on:myEvent 将会变成 v-on:myevent——导致 myEvent 不可能被监听到
* **推荐始终使用 kebab-case 的事件名**

### 自定义组件的 v-model
* 一个组件上的 v-model 默认会利用名为 value 的 prop 和名为 input 的事件
* 像单选框、复选框等类型的输入控件可能会将 value 特性用于不同的目的，model 选项可以用来避免这样的冲突
* 组件实例中设置好model对象属性，指定prop和event等属性值，当html使用组件时通过v-model指定要想绑定的变量
```
data: {
	learningVue: false
}

model: {
	prop: 'checked',
	event: 'change'
},
props: {
	checked: Boolean
},
template: `
	<input
		type="checkbox"
		v-bind:checked="checked"
		v-on:change="$emit('change', $event.target.checked)"
	>
`

<base-template v-model="learningVue"></base-template>
```
>learningVue的值会传入名为checked的prop，当base-template触发change事件并有一个新的值时，learningVue属性会更新
>>**仍然需要在组件的 props 选项里声明 checked 这个 prop**

### 将原生事件绑定到组件
* 使用 v-on 的 .native 修饰符，在一个组件的根元素上**直接**监听一个原生事件
```
<base-input v-on:focus.native="onFocus"></base-input>
```
* 在尝试监听一个类似 `<input> `的非常特定的元素时，比如上述 `<base-input>` 组件可能做了如下重构，所以根元素实际上是一个 `<label>`
```
<label>
  {{ label }}
  <input
    v-bind="$attrs"
    v-bind:value="value"
    v-on:input="$emit('input', $event.target.value)"
  >
</label>
```
* 这时，父级的 .native 监听器将静默失败。它不会产生任何报错，但是 onFocus 处理函数不会如你预期地被调用
* 为了解决这个问题，Vue 提供了一个 $listeners 属性，它是一个对象，里面包含了作用在这个组件上的所有监听器。
```
{
  focus: function (event) { /* ... */ }
  input: function (value) { /* ... */ },
}
```