# 服务启动的自动任务
```java
@Component
@Order(100)
public class LocalCommandLineRunner1 implements CommandLineRunner {
    @Override
    public void run(String... args) throws Exception {
        // TODO
    }
}
```
将首先需要将类在容器中注册, 类必须实现 `CommandLineRunner` 接口, `@order` 注解表示优先级, 数字越小优先级越大。run方法的参数来自于项目启动的参数, 即入口类mian方法的参数会传到这里。

# 定时任务
## 使用 `@Scheduled` 注解

使用 Schedule 注解需要配置类包括 `@EnableScheduling` 注解, 开启定时任务。

然后在bean中的方法上添加注解 `@Scheduled`, 设定任务, 参数可以设置执行的规律。默认是单线程执行。
```java
@Configuration
@EnableScheduling
public class LocalTimingTask {
    // 两次任务开始时间之间的间隔
    // @Scheduled(fixedRate = 2000)
    public void fixedRate() {
        System.out.println("fixedRate>>>>" + new Date());
    }
    // 上次任务结束和下次任务开始之间的间隔
    // @Scheduled(fixedDelay = 2000)
    public void fixedDelay() {
        System.out.println("fixedDelay>>>>" + new Date());
    }
    // 首次启动任务的延迟时间
    // @Scheduled(initialDelay = 2000, fixedDelay = 2000)
    public void initialDelay() {
        System.out.println("initialDelay>>>>" + new Date());
    }
}
```
> @schedule注解支持cron表达式, 支持丰富的规则设定。

## 第三方框架 Quartz
引用: 略

也需要开启任务注解 `@EnableScheduling`

有两个关键概念: jobDetail(要做的事情), 触发器(什么时候做)

定义Job有两种方法, 一种直接声明一个Bean就行, 另一种需要继承`QuartzJobBean`类, 然后实现默认方法。
