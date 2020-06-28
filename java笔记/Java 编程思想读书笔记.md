# Java 编程思想读书笔记
## 3 操作符
###  3.4. 赋值
基本数据类型赋值时，就是将数据直接放在变量的存储空间中，变量之间赋值是直接拷贝数据。对象赋值时，传递的是“引用”的值，变量间赋值是拷贝引用的地址。

###  3.4. 算数操作符
一元减号可以改变数据的正负，一元加号只是为了与一元减号相对应，唯一的作用是将较小类型的操作数提升为int。

###  3.11. 位移操作符
只对整数有效，char，byte，short使用时，会转型成int，结果也是int。int型只对低五位有效，long对低六位有效。
* `<<` 左移
* `>>` 有符号右移
* `>>>` 无符号右移

###  3.13. 字符串操作符
当多个 `+` 连续使用时，会将第一个任意一边出现字符串的 `+` 变成字符串的连接操作。

```java
"" + 1 + 2; // output: 12
1 + 2 + "23"; // output: 323
1 + 2 + 4 + "23"; // output: 723
1 + 2 + "23" + 4; // output: 3234
```

###  3.15. 类型转换操作符
既类型强转，可以对数值，亦可对变量。

窄化转换必须显式声明，扩展转换可以不显式声明
> float 和 double 转为整形时，默认截尾。

表达式中最大的数据类型决定了表达式最终结果的数据类型。

## 4 流程控制
### 4.3 循环
for循环
```java
for(initialization; Boolean-expression; step)
    statement;
```
第一次迭代前会执行初始化(initialization)，每次迭代前会先测试布尔表达式(Boolean-expression)，如果结果为 false，执行statement代码，代码执行结束会执行一次步进(step)。

初始化(initialization)和步进(step)均可包含多个表达式，用逗号分隔。

## 5 初始化与清理
### 5.7 构造器初始化
对象初始化顺序：静态对象->非静态对象

构造器可以看成静态方法。

## 7 复用类
### 7.2 初始化基类
new 一个新类时，会先构建父类再构建子类。

### 7.8 final 关键字
过去Java版本中，JVM会对 final 方法进行优化，使用静态化处理，将代码复制到调用方法初，提高执行效率。Java 6开始，这种优化已经开始摒弃，Java 8 不赞成这种优化。

类中所有的 private 方法都隐式地指定为 final。

private 方法，没办法在子类中使用 super 调用。

### 7.9 初始化及类的加载
父类静态域->子类静态域->父类普通域->父类构造方法/构造块->子类构造方法/构造块

## 8 多态
### 8.2 
将一个方法调用同一个方法主体关联起来被称作**绑定**。

后期绑定：运行时根据对象类型进行绑定。也叫动态绑定/运行时绑定。编译器一直不知道对象的类型，但方法调用几只能找到正确的方法体，并加以调用。

Java 中除了 static， finall（private 属于 finall），其他所有方法都是后期绑定。

声明 final 可以关闭动态绑定

因为 private 属性/方法无法在子类使用，非 private 才可以覆盖（重写），

并不是所有的东西都是多态，只有**普通的方法调用才是多态**。例如直接访问某个域（对象属性），这个访问在编译期就会进行解析。
```java
public class TestFather {
    public int theProperty = 0;
    public int getTheProperty() {
        return theProperty;
    }
}
public class TestSon extends TestFather {
    public int theProperty = 1;
    @Override
    public int getTheProperty() {
        return theProperty;
    }
    public int getFatherProperty() {
        return super.theProperty;
    }
}
class DemoApplicationTests {
	@Test
	void contextLoads() {
		TestSon son1 = new TestSon();
		System.out.println(son1.theProperty); // 1
		System.out.println(son1.getTheProperty()); // 1
		System.out.println(son1.getFatherProperty()); // 0
		TestFather son2 = new TestSon();
		System.out.println(son2.theProperty); // 0
		System.out.println(son2.getTheProperty()); // 1
	}
}
```
当子类转型成父类时，任何对属性的操作都将由编译器解析。上述例子中，`TestSon.theProperty`和`TestFather.theProperty`有不一样的存储空间。即`TestSon`实际上包含了自己的和继承的`theProperty`属性，在使用时必须指明时哪个，即显式调用。

使用private进行封装是比较好的规避方法。

静态方法不具有多态性。

### 8.3 构造器和多态

构造器隐式声明 static，