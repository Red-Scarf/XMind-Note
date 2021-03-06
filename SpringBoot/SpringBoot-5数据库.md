# 整合 JDBC
首先, 需要引入相应依赖

```xml
<dependency>
    <groupId>com.alibaba</groupId>
    <artifactId>druid-spring-boot-starter</artifactId>
    <version>1.1.10</version>
</dependency>
<dependency>
    <groupId>mysql</groupId>
    <artifactId>mysql-connector-java</artifactId>
    <version>8.0.21</version>
    <scope>runtime</scope>
</dependency>
<dependency>
    <groupId>org.springframework.boot</groupId>
    <artifactId>spring-boot-starter-jdbc</artifactId>
</dependency>
```
其中数据库连接依赖版本需要和实际数据库的依赖版本一致。

使用时, 先定义一个实体Bea, 然后针对该Bean定义一个Service, 用Autowired引入JdbcTemplate实例。

JdbcTemplate 中, 除了查询有几个API外, 增删改统一用 update 操作。

# 整合Mybatis
## 引入依赖
引入依赖与 JdbcTemplate 一致, 只是需要将`spring-boot-starter-jdbc`换成`mybatis-spring-boot-starter`。
```xml
<dependency>
    <groupId>org.mybatis.spring.boot</groupId>
    <artifactId>mybatis-spring-boot-starter</artifactId>
    <version>2.1.3</version>
</dependency>
```

## 配置 Mapper
用法和 JdbcTemplate 一样, 需要的 properties 配置也一致, 配置好直接写相应的 Mapper 接口。
```java
public interface UserMapper {
    @Select("select * from user;")
    List<User> getAllUsers();
    @Results({
            @Result(property = "id", column = "id"),
            @Result(property = "username", column = "u"),
            @Result(property = "address", column = "a")
    })
    @Select("select id, username as u, address as a from user where id=#{id};")
    User getUserById(Long id);
}
```

尽管注解比较方便, 但还是 xml 文件的 Mapper 更加的灵活, 功能更加的全面。

## 配置扫描 Mapper 文件
注解可以写在启动类上也可以写在配置类上。

## 使用 xml 文件配置
xml 文件首先要指定好作用域
```xml
<?xml version="1.0" encoding="UTF-8" ?>
<!DOCTYPE mapper
        PUBLIC "-//mybatis.org//DTD Mapper 3.0//EN"
        "http://mybatis.org/dtd/mybatis-3-mapper.dtd">
<mapper namespace="com.bootdemo.dao.StudentMapper">
    <select id="getAllStudent" resultType="com.bootdemo.dao.Student">
        select * from student;
    </select>
    <insert id="addStudent" parameterType="com.bootdemo.dao.Student">
        insert into student(name, address) values(#{name}, #{address});
    </insert>
    <update id="updateStudentById" parameterType="com.bootdemo.dao.Student">
        update student set name=#{name}, address=#{address} where id=#{id};
    </update>
    <delete id="deleteStudentById">
        delete from student where id=#{id};
    </delete>
</mapper>
```
这个 mapper 文件可以放在 interface 同一个文件夹下, 这样不需要额外配置就能被扫描到, 但 Maven打包时会被忽略, 需要额外配置 Maven。
```xml
<build>
    <resources>
        <resource>
            <directory>src/main/java</directory>
            <includes>
                <include>**/*.xml</include>
            </includes>
        </resource>
        <resource>
            <directory>src/main/resources</directory>
        </resource>
    </resources>
</build>
```
或者直接放在 resources 目录下, Maven 打包的时候无需额外的配置就会自动打包, 但必须要放和 interface 同一样的目录层级下, 这样 xml 文件和 interface 打包后又会在一起。这样放置, 没打包的时候需要去 properties 配置 mapper 的路径, 让 MyBatis 能扫描到。
```properties
mybatis.mapper-locations=classpath:mapper/*.xml
```

# JPA 概述
* Java Persistence API, 对象持久化接口。
* Java EE 的ORM标准, 使得应用以统一的方式访问持久层。
* JPA 是一种 ORM 规范, Hibernate 实现了 JPA, 功能上 JPA 是 Hibernate 的子集。
* Spring Data JPA 就是 Spring 自己实现的 JPA 规范。

## Spring Data JPA
Repository 是 Spring Data 的一个核薪接口, 当实现该接口后, 类会被 IOC 容器识别为一个 Repository Bean, 纳入到 IOC 容器中。

Repository 只有最基本的功能, 还有几个子接口拓展了一些功能: 
* CrudRepository: 继承 Repository, 实现了一组 CRUD 相关的方法。
* PagingAndSortingRepository: 继承 CrudRepository, 实现分页排序等。
* JpaRepository: 继承 PagingAndSortingRepository, 实现了一组 JPA 规范的方法。
* 自定义 XxxRepository 需要继承 JpaRepository, 才能具备通用的数据访问能力。
* JpaSpecificationExecutor: 不属于 Repository 体系, 实现一组 JPA Criteria 查询相关的方法。

## SpringBoot 整合 Hibernate
使用 `Entity` 注解时, 标记该对象为实体类, 项目启动时会针对该类生成一张表, 表名默认为类名, 属性 name 可以自定义表名。

`Id` 注解表示字段为表的id, `GeneratedValue` 表示该主键的自增策略。

对于实体类中的其他属性, 默认会根据属性名在表中生成相应的字段, 字段名与属性名一致, 可以使用 `Column` 注解配置字段的名称、长度、是否为空等属性。