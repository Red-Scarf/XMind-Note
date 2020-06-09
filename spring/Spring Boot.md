# Spring Boot

## parent 依赖
parent 不仅仅是统一管控依赖以及依赖版本。

### 基本功能
* 定义了`Java`编译版本为 1.8 。
* 使用`UTF-8`格式编码。
* 继承自`spring-boot-dependencies`，这个里边定义了依赖的版本，也正是因为继承了这个依赖，所以我们在写依赖时才不需要写版本号。
* 执行打包操作的配置。
* 自动化的资源过滤。
* 自动化的插件配置。
* 针对`application.properties`和`application.yml`的资源过滤，包括通过`profile`定义的不同环境的配置文件，例如`application-dev.properties`和`application-dev.yml`。

## 配置文件
### application.properties
虽然比较常见，但简洁程度和使用场景都不及 yaml，且数据是无序的，在一些需要路径匹配的配置中，顺序尤为重要。

#### 位置
application.properties 文件默认存放在以下位置：
* 当前项目根目录下的 config 目录下
* 当前项目的根目录下
* resources 目录下的 config 目录下
* resources 目录下

优先级如下

![](img/11-1.png)

可以自定义存放位置，通过 spring.config.location 属性来手动的指定配置文件位置。

![](img/11-2.png)

如果项目已经打包成 jar ，在启动命令中加入位置参数即可：
```
java -jar properties-0.0.1-SNAPSHOT.jar --spring.config.location=classpath:/javaboy/
```

#### 文件名

除了 application 之外，也可叫其他名字，需要明确指定配置文件的文件名。方式和指定路径一致，只不过此时的 key 是 spring.config.name。

![](img/11-3.png)

配置文件位置和文件名称可以同时自定义。

#### 属性注入

简单的属性注入可以直接写在 properties 文件中，
```java
public class Book {
    private Long id;
    private String name;
    private String author;
    //省略 getter/setter
}
```
```properties
book.name=三国演义
book.author=罗贯中
book.id=1
```
```java
@Component
public class Book {
    @Value("${book.id}")
    private Long id;
    @Value("${book.name}")
    private String name;
    @Value("${book.author}")
    private String author;
    //省略getter/setter
}
```
Book 对象本身也要交给 Spring 容器去管理，如果 Book 没有交给 Spring 容器，那么 Book 中的属性也无法从 Spring 容器中获取到值。


配置完成后，在 Controller 或者单元测试中注入 Book 对象，启动项目，就可以看到属性已经注入到对象中了。

可以通过xml文件引用 properties 文件
```xml
<context:property-placeholder location="classpath:book.properties"/>
```
Java 配置中，可以通过 @PropertySource 引入配置
```java
@Component
@PropertySource("classpath:book.properties")
public class Book {
    @Value("${book.id}")
    private Long id;
    @Value("${book.name}")
    private String name;
    @Value("${book.author}")
    private String author;
    //getter/setter
}
```
可以使用类型安全的属性注入，引入 @ConfigurationProperties(prefix = “book”) 注解，并且配置了属性的前缀，此时会自动将 Spring 容器中对应的数据注入到对象对应的属性中，就不用通过 @Value 注解挨个注入了，减少工作量并且避免出错。
```java
@Component
@PropertySource("classpath:book.properties")
@ConfigurationProperties(prefix = "book")
public class Book {
    private Long id;
    private String name;
    private String author;
    //省略getter/setter
}
```

### yaml