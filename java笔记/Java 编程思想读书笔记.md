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

构造器隐式声明 static。

尽可能不在构造器中调用其他的方法，尤其是子类的方法。唯一能安全调用的，只有父类的 final/private 方法。

### 8.4 协变返回类型

Java5 之后，子类重写父类的方法时，返回值可以是父类方法返回值的子类型。

### 8.5 用继承进行设计

尽量避免使用继承，优先选择使用组合，尤其是不能十分确定该使用继承还是组合时。

Java 在运行期间会对类型进行检查，称为“运行时类型识别（RTTI）”。

## 9 接口

### 9.1 抽象类和抽象方法

**包含抽象方法**的类必须声明为抽象类。继承抽象类时，必须实现其中所有的**抽象方法**。

```java
abstract class C {
    abstract void f();
}
```

### 9.2 接口

```java
interface service {
    void play();
    String say();
}
public class C implements service {
    public void play() {
        ……
    }
    public String say() {
        ……
    }
}
```

接口的方法默认时 public，继承后也只能时 public 方法，否则就会导致权限降低，违反“继承方法不能降低权限”，所以编译器会报错。

建议将实现类中的 public 方法都通过继承抽象类或者接口来定义，尽量避免自己创建独立的 public 方法。

### 9.4 多重继承接口

如果知道某一事物应该成为一个基类，那么第一选择应该是使它成为一个接口。

### 9.5 通过继承扩展接口

实现多个接口时，遇到同名方法，如果签名和返回值一致，不会有问题，如果不一致，则会报错。

### 9.6 适配接口

同一个接口会被多个类实现，典型使用接口的方式就是，声明一个接受接口类型的方法，而该接口的实现和向该方法传递的对象则取决于使用者。

> 策略模式

```java
public interface Service {
    void f (String s);
}
public class TestImp1 implements Service {
    @Override
    public void f(String s) {
        System.out.println(getClass() + " output: " + s);
    }
}
public class TestImp2 implements Service {
    @Override
    public void f(String s) {
        System.out.println(getClass() + " output: " + s);
    }
}
class DemoApplicationTests {
	void f (Service S, String sss) {
		S.f(sss);
	}
	@Test
	void contextLoads() {
		Service test1 = new TestImp1();
		this.f(test1, "你好");

		Service test2 = new TestImp2();
		this.f(test2, "不好");
	}
}
```
输出：

class local.boottest.demo.TestImp1 output: 你好

class local.boottest.demo.TestImp2 output: 不好

### 9.7 接口中的域

在 Java5 之前，因为接口中的任何域都是默认 static 和 final ，所以接口可以很便捷的用来创建常量组。

Java5 之后，有了 enum 关键字，所以不再使用接口创建常量组。

### 9.8 嵌套接口

接口可以嵌套在其他类或接口中。

### 9.10 接口与工厂

工厂模式可以生成遵循某个接口的对象（根据条件生成实现了相应接口的类对象）。