# Redis 服务器配置
想要连接redis，如果是普通服务器，需要关闭防火墙：
```
systemctl stop firewalld.service // 关闭防火墙
systemctl disable firewalld.service // 禁止防火墙自启
```
如果是云服务器，需要开启相应端口的访问权限。

然后是关闭redis保护模式，在redis.conf文件中
```
protected-mode no
```

然后是注释掉redis的ip地址绑定
```
# bind:127.0.0.1
``` 

# 使用 Jedis
## 连接 redis
创建一个普通的 javese maven 工程，依赖加上
```
<dependency>
    <groupId>redis.clients</groupId>
    <artifactId>jedis</artifactId>
    <version>2.9.0</version>
</dependency>
```

测试代码
```java
public static void main(String[] args) {
    Jedis jedis = new Jedis("xxx.xxx.xxx.xxx", xxxx);
    // jedis.auth("xxxx"); // 如果redis设置密码
    String ping = jedis.ping();
    System.out.println(ping);
}
```

## 使用 redis
Jedis类中方法名称和redis中的命令基本是一致的，看到方法名知道是干什么的

频繁的创建和销毁连接会影响性能，可以采用连接池来部分的解决这个问题：
```java
public static void main(String[] args) {
    GenericObjectPoolConfig config = new GenericObjectPoolConfig();
    config.setMaxTotal(100);
    config.setMaxIdle(20);
    JedisPool jedisPool = new JedisPool(config, "xxx.xxx.xxx.xxx", xxxx);
    // jedis.auth("xxxx"); // 如果redis设置密码
    Jedis jedis = jedisPool.getResource();
    System.out.println(jedis.ping());
}
```