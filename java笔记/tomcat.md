# Tomcat 原理

Tomcat是运行在JVM中的一个进程，是一个容器，里面运行着若干service，每个service中有若干servlet。

tomcat 通过运行一个socketServer 监听相应端口，对进入的请求进行处理和转发。

请求进入后，先交由HTTP服务处理，将HTTP报文封装成ServletRequest对象。Servlet容器再根据配置好的映射关系，将请求转发到相应的Servlet中（利用反射创建Servlet）。请求处理结束后，再原路返回。