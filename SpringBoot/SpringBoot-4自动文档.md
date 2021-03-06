# 整合swagger
因为spring boot 没有直接配置swagger, 所以需要手动配置依赖, 并且要指定版本。

```java
@Configuration
@EnableSwagger2
public class SwaggerConfig {
    @Bean
    public Docket createRestApi() {
        return new Docket(DocumentationType.SWAGGER_2)
            .pathMapping("/")
            .select()
            .apis(RequestHandlerSelectors.basePackage("com.bootdemo"))
            .paths(PathSelectors.any())
            .build().apiInfo(new ApiInfoBuilder()
                .title("Swagger 自动文档")
                .description("详细信息...")
                .version("9.0")
                .contact(new Contact("你好你好", "www.baidu.com", "aaa@aaa.com"))
                .license("The Apache License")
                .licenseUrl("http://www.bootdemo.com")
                .build()
            );
    }
}
```

swagger 注解
* @Api注解可以用来标记当前Controller的功能。
* @ApiOperation注解用来标记一个方法的作用。
* @ApiImplicitParam注解用来描述一个参数, 可以配置参数的中文含义, 也可以给参数设置默认值, 这样在接口测试的时候可以避免手动输入。
* 如果有多个参数, 则需要使用多个@ApiImplicitParam注解来描述, 多个@ApiImplicitParam注解需要放在一个@ApiImplicitParams注解中。
* 需要注意的是, @ApiImplicitParam注解中虽然可以指定参数是必填的, 但是却不能代替@RequestParam(required = true), 前者的必填只是在Swagger2框架内必填, 抛弃了Swagger2, 这个限制就没用了, 所以假如开发者需要指定一个参数必填, @RequestParam(required = true)注解还是不能省略。
* 如果参数是一个对象（例如上文的更新接口）, 对于参数的描述也可以放在实体类中。例如下面一段代码: 
```java
@ApiModel
public class User {
    @ApiModelProperty(value = "用户id")
    private Integer id;
}
```

## Swagger 与 Security
如果我们的Spring Boot项目中集成了Spring Security, 那么如果不做额外配置, Swagger2文档可能会被拦截, 此时只需要在Spring Security的配置类中重写configure方法, 添加如下过滤即可: 
```java
@Override public void configure(WebSecurity web) throws Exception {
    web.ignoring()
        .antMatchers("/swagger-ui.html")
        .antMatchers("/v2/**")
        .antMatchers("/swagger-resources/**");
}
```