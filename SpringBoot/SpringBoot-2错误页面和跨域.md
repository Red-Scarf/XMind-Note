# 全局错误页面
Spring Boot 直接配置资源就能使用报错页面。404.html 命名即404错误时直接使用404.html, 4xx.html即所有4开头的错误都可以使用。

静态页面路径: classpath:/static/error

动态页面路径: classpath:/template/error

SpringBoot查找错误页面的逻辑: 发生404错误->查到动态404.html->查到静态404.html->查到动态4xx.html->查到静态4xx.html

## 动态页面数据
动态错误页面默认有以下数据可以使用: path, error, message, timestamp, status

想要自定义这些参数, 有两种方法: 

* 直接实现 `ErrorAttributies` 接口
* 继承 `DefaultErrorAttributes` (推荐), 因为该类中对异常数据的处理已完成。

## 自定义异常视图
因为 `DefaultErrorViewResolver` 是在 `ErrorMvcAutoConfiguration` 类中提供的实例, 开发者没有提供相应实例时, 会使用 `DefaultErrorViewResolver` , 开发者提供了自己的 `ErrorViewResolver` 之后, 默认配置失效。

```java
/**
 * 自定义异常视图解析类
 */
@Component
public class LocalErrorViewResolver extends DefaultErrorViewResolver {
    public LocalErrorViewResolver(ApplicationContext context, ResourceProperties properties) {
        super(context, properties);
    }
    @Override
    public ModelAndView resolveErrorView(HttpServletRequest request, HttpStatus status, Map<String, Object> model) {
        // aaa/123 实际指 classpath:/aaa/123.html
        return new ModelAndView("/aaa/123", model);
    }
}
```
> 想要自定义模型数据, 重写一个model, 将参数中的的model拷贝过去（因为这个参数model是UnmodifiableMap类型, 不可编辑）

# CORS 跨域问题
因为同源策略, 协议、域名、端口要相同, 传统JSONP只支持get请求, 现在普遍使用CORS实现跨域。

使用 `@CrossOrigin` 注解就能设置某一接口接受某域名的跨域, get post都一样。
```java
@RestController
public class HelloController {
    @CrossOrigin("http://localhost:9091")
    @PostMapping("/hello")
    public String hello2() {
        return "post hello";
    }
}
```

也可以通过配置类进行全局配置
```java
@Configuration
public class WebMVCConfig implements WebMvcConfigurer {
    @Override
    public void addCorsMappings(CorsRegistry registry) {
        registry.addMapping("/**")
            .allowedOrigins("http://localhost:9091")
            .allowedMethods("*")
            .allowedHeaders("*");
    }
}
```
