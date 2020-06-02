## 1 Java基本功
### 1.1 Java 入门
### 1.2 Java 语法
#### 1.2.6 break, continue, return
* break只跳出一层循环
#### 1.2.7 Java 泛型
#### 泛型的使用方式
* 泛型类
```java
// 此处T可以随便写为任意标识，常见的如T、E、K、V等形式的参数常用于表示泛型
// 在实例化泛型类时，必须指定T的具体类型
public class Generic<T>{ 
    private T key;
    public Generic(T key) { 
        this.key = key;
    }
    public T getKey(){ 
        return key;
    }
}
// 实例化
Generic<Integer> genericInteger = new Generic<Integer>(123456);
```
* 泛型接口
```java
public interface Generator<T> {
    public T method();
}
// 实现接口，不指定类型
class GeneratorImpl<T> implements Generator<T>{
    @Override
    public T method() {
        return null;
    }
}
// 指定类型
class GeneratorImpl<T> implements Generator<String>{
    @Override
    public String method() {
        return "hello";
    }
}
```
* 泛型方法
```java
public static < E > void printArray( E[] inputArray )
{         
    for ( E element : inputArray ){        
        System.out.printf( "%s ", element );
    }
    System.out.println();
}
// 使用
// 创建不同类型数组： Integer, Double 和 Character
Integer[] intArray = { 1, 2, 3 };
String[] stringArray = { "Hello", "World" };
printArray( intArray  ); 
printArray( stringArray  ); 
```

#### 常用的通配符为： T, E, K, V, ?
> 本质上这些个都是通配符，没啥区别，只不过是编码时的一种约定俗成的东西。比如上述代码中的 T ，我们可以换成 A-Z 之间的任何一个 字母都可以，并不会影响程序的正常运行，但是如果换成其他的字母代替 T ，在可读性上可能会弱一些。
* ? 表示不确定的 java 类型，无界通配符
* T (type) 表示具体的一个java类型
* K V (key value) 分别代表java键值中的Key Value
* E (element) 代表Element
* 上界通配符 < ? extends E>
* 下界通配符 < ? super E>

#### 类型擦除
使用泛型，仅在编译期生效，Java 编译时会检测泛型指定的类型是否使用出错，编译完成后，泛型消失，将泛型转换成原始类型。

> `List<Integer>` 或 `List<String>` 编译后都是 `List<Object>`

上述例子中，`Object`就是原始类型，而在`public class Pair<T extends Comparable> {}`中`Comparable`就是原始类型。

* 在不指定泛型的情况下，泛型变量的类型为该方法中的几种类型的同一父类的最小级，直到Object
* 在指定泛型的情况下，该方法的几种类型必须是该泛型的实例的类型或者其子类

泛型的检查是在编译之前。
```java
ArrayList<String> list1 = new ArrayList(); //第一种 情况
ArrayList list2 = new ArrayList<String>(); //第二种 情况
```
第一种情况，可以实现与完全使用泛型参数一样的效果，第二种则没有效果。

因为类型检查就是编译时完成的，new ArrayList()只是在内存中开辟了一个存储空间，可以存储任何类型对象，而真正设计类型检查的是它的引用，因为我们是使用它引用list1来调用它的方法，比如说调用add方法，所以list1引用能完成泛型类型的检查。而引用list2没有使用泛型，所以不行。

类型检查就是针对引用的，谁是一个引用，用这个引用调用泛型方法，就会对这个引用调用的方法进行类型检测，而无关它真正引用的对象。

泛型编译时不允许强转
```java
ArrayList<String> list1 = new ArrayList<Object>(); //编译错误  
ArrayList<Object> list2 = new ArrayList<String>(); //编译错误
```

```java
public E get(int index) {
    RangeCheck(index);  
    return (E) elementData[index];  
}
```
如果使用时，E=Date，则泛型会在编译时，将`(E)`自动转换为`(Date)`

**泛型类中的静态方法和静态变量不可以使用泛型类所声明的泛型类型参数。**
* 因为泛型类中的泛型参数的实例化是在定义对象的时候指定的，而静态变量和静态方法不需要使用对象来调用。
* 但是要注意区分下面的一种情况。因为这是一个泛型方法，在泛型方法中使用的T是自己在方法中定义的 T，而不是泛型类中的T。
```java
public class Test2<T> {
    public static <T >T show(T one){ //这是正确的
        return null;
    }
}
```

#### 1.2.8 ==和equals的区别
* `==` : 它的作用是判断两个对象的地址是不是相等。即判断两个对象是不是同一个对象。**(基本数据类型==比较的是值，引用数据类型==比较的是内存地址)**
> 因为 Java 只有值传递，所以，对于 == 来说，不管是比较基本数据类型，还是引用数据类型的变量，其本质比较的都是值，只是引用类型变量存的值是对象的地址。
* equals() : 它的作用也是判断两个对象是否相等，它不能用于比较基本数据类型的变量。equals()方法存在于Object类中，而Object类是所有类的直接或间接父类。
```java
// Object类equals()方法
public boolean equals(Object obj) {
     return (this == obj);
}
```

#### 1.2.9. hashCode()与 equals()
* `hashCode()`的作用是获取哈希码，它实际上是返回一个 int 整数。
```java
public native int hashCode();
```
* hashCode 意义和使用场景
> 两个对象相等，`hashCode`一定相等，`hashCode`相等的对象，不一定是相同的对象，`hashCode`不同的对象一定不是相同的对象。

利用以上概念，可以在比对时，先用`hashCode`比对，如果相同，再调用`equals()`方法进行对比，可以降低`equals()`调用次数。

* hashCode() 和 equals() 关系
当 equals 方法被重写时，通常有必要重写 hashCode 方法，以维护 hashCode 方法的常规协定。该协定声明相等对象必须具有相等的哈希码。

> Java默认的`hashCode()`返回的是当前对象的地址hash值，如果重写`equal()`方法而不重写`hashCode()`，则`equal()`返回`true`的方法，`hashCode()`永远不可能相等。

### 1.3 Java 流程控制
### 1.4 数组
### 1.5 方法
#### 1.5.2 Java 的方法参数传递
java方法只有值传递，参数是基础类型时，传递的时值的拷贝，参数是对象时，传递的时对象引用值的拷贝。
所以，对形参的对象属性进行修改时，实参对象的属性也会改变，因为引用指向同一个地址同一个对象实例。如果是单纯的对整个对象进行对调，比如变量a和变量b对调，那么只是对象的引用的拷贝对调，不会对实参有影响。

== 与 equals：基本数据类型==比较的是值，引用数据类型==比较的是内存地址。

String 中的 equals 方法是被重写过的，因为 object 的 equals 方法是比较的对象的内存地址，而 String 的 equals 方法比较的是对象的值。当创建 String 类型的对象时，虚拟机会在常量池中查找有没有已经存在的值和要创建的值相同的对象，如果有就把它赋给当前引用。如果没有就在常量池中重新创建一个 String 对象。

## 2 Java 面向对象
### 2.1 类和对象

#### 面向过程和面向对象
面向过程性能比面向对象的性能高，是因为 Java 是半编译语言，最终的执行代码并不是可以直接被 CPU 执行的二进制机械码。面向过程语言大多都是直接编译成机械码在电脑上执行，并且其它一些面向过程的脚本语言性能也并不一定比 Java 好。

#### 在 Java 中定义一个不做事且没有参数的构造方法的作用
Java 在初始化子类时，如果没有显式调用父类`super()`，Java会默认调用父类的没有参数的构造函数，倘若父类没有定义无参构造函数(定义了有参的构造函数，没有显式定义无参构造函数，没有显式定义构造函数时，Java会自动生成无参构造函数)，则编译报错。

#### 对象实体与对象引用有何不同
对象引用指向对象实体(实例)，对象实例存在堆内存中，对象引用在方法中，所以是存在栈内存中。

#### 对象的相等与指向他们的引用相等
对象的相等，比的是内存中存放的内容是否相等。而引用相等，比较的是他们指向的内存地址是否相等。

### 2.2 面向对三大特征
#### 封装
封装是指把一个对象的状态信息（也就是属性）隐藏在对象内部，不允许外部对象直接访问对象的内部信息。
> getter 和 setter

#### 继承
子类拥有父类对象所有的属性和方法（包括私有属性和私有方法），但是父类中的私有属性和方法子类是无法访问，只是拥有。

#### 多态
表示一个对象具有多种的状态。具体表现为父类的引用指向子类的实例。
* 对象类型和引用类型之间具有继承（类）/实现（接口）的关系；
* 对象类型不可变，引用类型可变；
* 方法具有多态性，属性不具有多态性；
* 引用类型变量发出的方法调用的到底是哪个类中的方法，必须在程序运行期间才能确定；
* 多态不能调用“只在子类存在但在父类不存在”的方法；
* 如果子类重写了父类的方法，真正执行的是子类覆盖的方法，如果子类没有覆盖父类的方法，执行的是父类的方法。

### 2.3 修饰符

### 2.4 接口和抽象类
#### 区别
* Java8 之前接口的方法不能有实现，只能声明。抽象类可以有非抽象的方法。
* 接口只能 static 、 final 变量，抽象类没要求。
* 类可以实现多个接口，只能继承一个类。接口自己可以继承多个其他接口。
* 接口方法默认 public ，抽象方法可以是 public 、 protect 、 default ，不能是 private 。
* 抽象是对类的抽象，是一种模板设计，而接口是对行为的抽象，是一种行为的规范。
> Java8 开始，接口可以使用静态方法，直接使用接口名调用，实现类和实例不能调用。
Java9 开始接口允许私有方法和私有静态变量。

### 2.5 其他
#### String 、 StringBuffer 、 StringBuilder
* String 使用 final 修饰字符数组，所以才说 String 不可变，不能在不改变引用地址的前提下改变 String 中的值。
```java
private final char value[]
```
* `StringBuilder`与`StringBuffer`均继承自`AbstractStringBuilder`，但`AbstractStringBuilder`没有使用 final 修饰字符数组。
* `StringBuilder`与`StringBuffer`都是调用父类的构造函数。
*  `StringBuilder`对方法或调用的方法加了同步锁，一定程度上可以认为是线程安全的。
* `String`每次修改时，都是生成一个新的String对象，然后引用指向该对象，所以性能最差。

#### Object 类的常见方法总结
```java
public final native Class<?> getClass()
```
返回当前运行时对象的Class对象
```java
public native int hashCode()
```
返回对象的哈希码，主要使用在哈希表中
```java
public boolean equals(Object obj)
```
比较2个对象的内存地址是否相等
```java
protected native Object clone() throws CloneNotSupportedException
```
克隆方法
```java
public String toString()
```
返回类的名字@实例的哈希码的16进制的字符串。建议Object所有的子类都重写这个方法。
```java
public final native void notify()
```
唤醒一个在此对象监视器上等待的线程(监视器相当于就是锁的概念)。如果有多个线程在等待只会任意唤醒一个。
```java
public final native void notifyAll()
```
唤醒在此对象监视器上等待的所有线程
```java
public final native void wait(long timeout) throws InterruptedException
```
暂停线程的执行。注意：sleep方法没有释放锁，而wait方法释放了锁 。timeout是等待时间。
```java
public final void wait(long timeout, int nanos) throws InterruptedException
```
多了nanos参数，这个参数表示额外时间（以毫微秒为单位，范围是 0-999999）
```java
public final void wait() throws InterruptedException
```
跟之前的2个wait方法一样，只不过该方法一直等待，没有超时时间这个概念
```java
protected void finalize() throws Throwable
```
实例被垃圾回收器回收的时候触发的操作

#### 序列化
对于不想进行序列化的变量，使用`transient`关键字修饰。
`transient`关键字的作用是：阻止实例中那些用此关键字修饰的的变量序列化；当对象被反序列化时，被`transient`修饰的变量值不会被持久化和恢复。`transient`只能修饰变量，不能修饰类和方法。

#### 键盘输入
Scanner
```java
Scanner input = new Scanner(System.in);
String s  = input.nextLine();
input.close();
```
BufferedReader
```java
BufferedReader input = new BufferedReader(new InputStreamReader(System.in));
String s = input.readLine();
```

### Java 反射
#### 实际使用
```java
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
