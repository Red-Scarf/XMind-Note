## Vue入门09-表单输入绑定
### 基础
#### 定义
* v-model 本质上不过是语法糖
* v-model 负责监听用户的输入事件以更新数据，并对一些极端场景进行一些特殊处理
* v-model 会忽略所有表单元素的 value、checked、selected 特性的初始值,而总是将 Vue 实例的数据作为数据来源
* 你应该通过 JavaScript 在组件的 data 选项中声明初始值。
* 可以用 v-model 指令在表单 `<input>`、`<textarea>` 及 `<select>` 元素上创建双向数据绑定
* v-model 会根据控件类型自动选取正确的方法来更新元素

**v-model 在内部为不同的输入元素使用不同的属性并抛出不同的事件**
* text 和 textarea 元素使用 value 属性和 input 事件；
* checkbox 和 radio 使用 checked 属性和 change 事件；
* select 字段将 value 作为 prop 并将 change 作为事件。
* **对于需要使用输入法 (如中文、日文、韩文等) 的语言，v-model 不会在输入法组合文字过程中得到更新。如果想处理这个过程，请使用 input 事件。**

### form标签
#### 文本
```
<input v-model="message" placeholder="edit me">
<p>Message is: {{ message }}</p>
```
#### 多行文本
```
<span>Multiline message is:</span>
<p style="white-space: pre-line;">{{ message }}</p>
<br>
<textarea v-model="message" placeholder="add multiple lines"></textarea>
```
>在文本区域插值 (`<textarea>{{text}}</textarea>`) 并不会生效，应用 v-model 来代替。
#### 复选框
单个复选框，绑定到布尔值
```
<input type="checkbox" id="checkbox" v-model="checked">
<label for="checkbox">{{ checked }}</label>
```
多个复选框，绑定到同一个数组
```
<input type="checkbox" id="jack" value="Jack" v-model="checkedNames">
<label for="jack">Jack</label>
<input type="checkbox" id="john" value="John" v-model="checkedNames">
<label for="john">John</label>
<input type="checkbox" id="mike" value="Mike" v-model="checkedNames">
<label for="mike">Mike</label>
```
#### 单选按钮
```
<input type="radio" name="one" value="One" v-model="picked">
```
#### 下拉框
##### 单选时
```
<select v-model="selected">
  <option disabled value="">请选择</option>
  <option>A</option>
  <option>B</option>
  <option>C</option>
</select>
```
>如果 v-model 表达式的初始值未能匹配任何选项，`<select>` 元素将被渲染为“未选中”状态。在 iOS 中，这会使用户无法选择第一个选项。因为这样的情况下，iOS 不会触发 change 事件。因此，更推荐像上面这样提供一个值为空的禁用选项。
##### 多选时(绑定一个数组)
```
<select v-model="selected" multiple style="width: 50px;">
  <option>A</option>
  <option>B</option>
  <option>C</option>
</select>
```
##### 用v-for动态渲染
```
<option v-for="option in options" v-bind:value="option.value">
  {{ option.text }}
</option>
```
### 值绑定
对于单选按钮，复选框及选择框的选项，v-model 绑定的值通常是静态字符串 (对于复选框也可以是布尔值)。
但是有时我们可能想把值绑定到 Vue 实例的一个动态属性上，这时可以用 v-bind 实现，并且这个属性的值可以不是字符串。
#### 复选框
```
<input type="checkbox" name="toggle" v-model="toggle" true-value="yes" false-value="no">
```
>true-value 和 false-value 特性并不会影响输入控件的 value 特性，因为浏览器在提交表单时并不会包含未被选中的复选框。如果要确保表单中这两个值中的一个能够被提交，(比如“yes”或“no”)，请换用单选按钮。
#### 单选按钮
```
<input type="radio" v-model="pick" v-bind:value="a">
```
#### 下拉框选项
```
<select v-model="selected">
  <!-- 内联对象字面量 -->
  <option v-bind:value="{ number: 123 }">123</option>
</select>
```
### 修饰符
#### .lazy
在默认情况下，v-model 在每次 input 事件触发后将输入框的值与数据进行同步 (除了上述输入法组合文字时)。你可以添加 lazy 修饰符，从而转变为使用 change 事件进行同步
```
<!-- 在“change”时而非“input”时更新 -->
<input v-model.lazy="msg" >
```
#### .number
自动将用户的输入值转为数值类型。**如果这个值无法被 parseFloat() 解析，则会返回原始的值。**
```
<input v-model.number="age" type="number">
```
#### .trim
自动过滤用户输入的首尾空白字符
```
<input v-model.trim="msg">
```