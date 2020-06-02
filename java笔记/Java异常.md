## Java 异常

![](img/68747470733a2f2f696d676b722e636e2d626a2e7566696c656f732e636f6d2f31393937303363652d613162362d343936382d396562302d3136316138323137353037652e706e67.png)

![](img/68747470733a2f2f696d676b722e636e2d626a2e7566696c656f732e636f6d2f33633435353239662d383931352d343438622d383136612d3336306638636237336663372e706e67.png)

### 异常层次结构
#### 异常的类
Java 中，异常共同父类是`Throwable`，其下有两个子类：`Exception`（异常） 和 `Error`（错误）。

* Error：程序无法处理的**错误**，一般与编码人员无关。因为大多涉及虚拟机或者物理机，所以没必要去处理错误。

Exception： 是程序本身可以处理的**异常**。其中几个重要的子类：`RuntimeException`(虚拟机抛出), `NullPointerException`(空指针,一般是没有引用对象), `ArithmeticException`(算术运算异常), `ArrayIndexOutOfBoundsException`(下标越界)

> 异常能被程序本身处理，错误是无法处理。

#### Throwable 常用方法
* `getMessage()`:返回异常发生时的简要描述
* `toString()`:返回异常发生时的详细信息
* `getLocalizedMessage()`:返回异常对象的本地化信息。使用`Throwable`的子类覆盖这个方法，可以生成本地化信息。
* `printStackTrace()`:在控制台上打印`Throwable`对象封装的异常信息

#### try-catch-finally
* try和catch中,如果有任何的return,而同时又有finally存在时,会在执行return之前,执行finally的代码. 如果finally代码中有return,则会覆盖try和catch中的return.
* finall代码块第一行就错误,则finally代码不执行.

#### try-with-resources
* Java7 之后开始有`try-with-resources`语句块,在try后的括号中初始化资源,当代码执行结束之后,会自动关闭资源.当发生错误时,会在catch之前关闭资源.
* 只有实现了`AutoCloseable`接口的类才能在其中初始化.
* try中可以声明多个资源类,用分号隔开,try中的多个资源在初始化过程中,一旦发生异常,那些已经完成初始化的资源,将按照与其创建时相反的顺序依次关闭.
* 当close方法中抛出异常时,因为处于try转移到catch过程中,异常会被抑制.

```java
try (Scanner scanner = new Scanner(new File("test.txt"))) {
    while (scanner.hasNext()) {
        System.out.println(scanner.nextLine());
    }
} catch (FileNotFoundException fnfe) {
    fnfe.printStackTrace();
}
```