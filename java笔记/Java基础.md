## 1 Java基本功
### 1.1 Java入门
### 1.2 Java语法
### 1.3 Java流程控制
### 1.4 数组
### 1.5 方法
#### 1.5.2 Java的方法参数传递
java方法只有值传递，参数是基础类型时，传递的时值的拷贝，参数是对象时，传递的时对象引用值的拷贝。
所以，对形参的对象属性进行修改时，实参对象的属性也会改变，因为引用指向同一个地址同一个对象实例。如果是单纯的对整个对象进行对调，比如变量a和变量b对调，那么只是对象的引用的拷贝对调，不会对实参有影响。

== 与 equals：基本数据类型==比较的是值，引用数据类型==比较的是内存地址。

String 中的 equals 方法是被重写过的，因为 object 的 equals 方法是比较的对象的内存地址，而 String 的 equals 方法比较的是对象的值。当创建 String 类型的对象时，虚拟机会在常量池中查找有没有已经存在的值和要创建的值相同的对象，如果有就把它赋给当前引用。如果没有就在常量池中重新创建一个 String 对象。

### Java反射
#### 实际使用
```
Class stuClass = Class.forName("fanshe.field.Student"); // 获取类的定义
Object obj = stuClass.getConstructor().newInstance(); // 获取对象
fieldArray = stuClass.getDeclaredFields(); // 获取所有字段
Field f = stuClass.getField("name"); // 获取某一字段
f.set(obj, "刘德华"); // 为对象的字段设值
// 字段必须通过“类定义”获取，而设值必须是实际的对象的成员

Method[] methodArray = stuClass.getMethods(); // 所有的公有方法
methodArray = stuClass.getDeclaredMethods(); // 所有方法
Method m = stuClass.getMethod("show1", String.class); // 获取指定方法
m.invoke(obj, "刘德华"); // 反射调用指定方法
m = stuClass.getDeclaredMethod("show4", int.class); // 获取私有方法
```
可以利用反射绕过泛型检查，因为泛型主要处于编译期，编译完成后会“泛型擦除”，即不再检查类型。此时使用反射可以对赋予其他类型的值。

### Java抽象类和接口的区别
* 接口的方法默认是 public，所有方法在接口中不能有实现，抽象类可以有非抽象的方法
* 接口中的实例变量默认是 final 类型的，而抽象类中则不一定
* 一个类可以实现多个接口，但最多只能实现一个抽象类
* 一个类实现接口的话要实现接口的所有方法，而抽象类不一定
* 接口不能用 new 实例化，但可以声明，但是必须引用一个实现该接口的对象 从设计层面来说，抽象是对类的抽象，是一种模板设计，接口是行为的抽象，是一种行为的规范。
