## 1 Java基本功
### 1.1 Java 入门
### 1.2 Java 语法
### 1.3 Java 流程控制
### 1.4 数组
### 1.5 方法
#### 1.5.2 Java 的方法参数传递
java方法只有值传递，参数是基础类型时，传递的时值的拷贝，参数是对象时，传递的时对象引用值的拷贝。
所以，对形参的对象属性进行修改时，实参对象的属性也会改变，因为引用指向同一个地址同一个对象实例。如果是单纯的对整个对象进行对调，比如变量a和变量b对调，那么只是对象的引用的拷贝对调，不会对实参有影响。

== 与 equals：基本数据类型==比较的是值，引用数据类型==比较的是内存地址。

String 中的 equals 方法是被重写过的，因为 object 的 equals 方法是比较的对象的内存地址，而 String 的 equals 方法比较的是对象的值。当创建 String 类型的对象时，虚拟机会在常量池中查找有没有已经存在的值和要创建的值相同的对象，如果有就把它赋给当前引用。如果没有就在常量池中重新创建一个 String 对象。

### Java 反射
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

### Java 抽象类和接口的区别
* 接口的方法默认是 public，所有方法在接口中不能有实现，抽象类可以有非抽象的方法
* 接口中的实例变量默认是 final 类型的，而抽象类中则不一定
* 一个类可以实现多个接口，但最多只能实现一个抽象类
* 一个类实现接口的话要实现接口的所有方法，而抽象类不一定
* 接口不能用 new 实例化，但可以声明，但是必须引用一个实现该接口的对象 从设计层面来说，抽象是对类的抽象，是一种模板设计，接口是行为的抽象，是一种行为的规范。

### Java 泛型
#### 类型擦除
使用泛型，仅在编译期生效，Java 编译时会检测泛型指定的类型是否使用出错，编译完成后，泛型消失，将泛型转换成原始类型。

> `List<Integer>` 或 `List<String>` 编译后都是 `List<Object>`

上述例子中，`Object`就是原始类型，而在`public class Pair<T extends Comparable> {}`中`Comparable`就是原始类型。

* 在不指定泛型的情况下，泛型变量的类型为该方法中的几种类型的同一父类的最小级，直到Object
* 在指定泛型的情况下，该方法的几种类型必须是该泛型的实例的类型或者其子类

泛型的检查是在编译之前。
```
ArrayList<String> list1 = new ArrayList(); //第一种 情况
ArrayList list2 = new ArrayList<String>(); //第二种 情况
```
第一种情况，可以实现与完全使用泛型参数一样的效果，第二种则没有效果。

因为类型检查就是编译时完成的，new ArrayList()只是在内存中开辟了一个存储空间，可以存储任何类型对象，而真正设计类型检查的是它的引用，因为我们是使用它引用list1来调用它的方法，比如说调用add方法，所以list1引用能完成泛型类型的检查。而引用list2没有使用泛型，所以不行。

类型检查就是针对引用的，谁是一个引用，用这个引用调用泛型方法，就会对这个引用调用的方法进行类型检测，而无关它真正引用的对象。

泛型编译时不允许强转
```
ArrayList<String> list1 = new ArrayList<Object>(); //编译错误  
ArrayList<Object> list2 = new ArrayList<String>(); //编译错误
```

```
public E get(int index) {
    RangeCheck(index);  
    return (E) elementData[index];  
}
```
如果使用时，E=Date，则泛型会在编译时，将`(E)`自动转换为`(Date)`

**泛型类中的静态方法和静态变量不可以使用泛型类所声明的泛型类型参数。**
* 因为泛型类中的泛型参数的实例化是在定义对象的时候指定的，而静态变量和静态方法不需要使用对象来调用。
* 但是要注意区分下面的一种情况。因为这是一个泛型方法，在泛型方法中使用的T是自己在方法中定义的 T，而不是泛型类中的T。
```
public class Test2<T> {
    public static <T >T show(T one){ //这是正确的
        return null;
    }
}
```