## J2EE基础知识
### servlet
* `HttpServletRequest`负责接收用户请求,在`doGet()`和`doPost()`中作出回应,将`HttpServletResponse`反馈给用户。
* 初始化调用`init()`,销毁调用`destroy()`。
* 需要配置web.xml,可以设置多个URL访问,但不是线程安全的。

优点:
* 只需要一个进程,加载一个JVM,多个请求时,只加载一个类,降低开销。
* 所有动态加载的类可以实现对网络协议以及请求解码的共享。
* Servlet能直接和Web服务器交互,Servlet还能在各个程序之间共享数据。

### Servlet 方法和生命周期
前三个是生命周期
* void init(ServletConfig config) throws ServletException
* void service(ServletRequest req, ServletResponse resp) throws ServletException, java.io.IOException
* void destroy()
* java.lang.String getServletInfo()
* ServletConfig getServletConfig()

生命周期: Web容器加载Servlet并实例化,Servlet生命周期开始,容器运行`init()`方法进行初始化。请求过来时,会根据请求的Method决定是调用`doGet()`还是`doPost()`。服务器关闭或者项目卸载时,执行`destroy()`销毁Servlet。

### 转发和重定向
* 转发(forward): 服务器行为,通过RequestDispatcher对象的forward方法实现。用户浏览器无法看到是否转发,因为URL没变。转发时,可以共享request中的数据(可能是因为浏览器URL没变)。效率比redirect高。
```java
request.getRequestDispatcher("login_success.jsp").forward(request, response);
```
* 重定向(redirect): 客户端行为,服务器放回状态码等信息,客户端以此到新网址请求数据。浏览器的URL会改变。不能共享request(可能因为URL变化)。

#### 自动刷新
```java
// 其中5为时间，单位为秒。URL指定就是要跳转的页面（如果设置自己的路径，就会实现每过5秒自动刷新本页面一次）
Response.setHeader("Refresh","5;URL=http://localhost:8080/servlet/example.htm");
```

#### 线程安全与否
尽可能将变量放在`doPost()`和`doGet()`方法中。只读属性最好定义为final类型的。