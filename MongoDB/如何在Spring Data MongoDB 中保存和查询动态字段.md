原文: https://stackoverflow.com/questions/46466562/how-to-save-and-query-dynamic-fields-in-spring-data-mongodb

我在搜索如何解决问题的时候，发现了一篇问答正好能解决我的问题，所有我用自己浅薄的英语做个简单的翻译。

## 这是一篇 Stack Overflow 上的问答

有人提了个问题，在使用 Spring Boot 1.4.x 和 Spring Data MongoDB 时，想要动态存储实体的字段，而不是直接用一个已经定义好的实体的固定字段存储。

虽然可以在实体类内部创建一个Map属性来存动态字段，但提问者想要的是所有的字段都处于MongoDB文档的顶层，不想要在字段里面还存在额外的数据结构。

提问者尝试后发现，直接将实体类继承自HashMap是不行的
```
Error creating bean with name 'entryRepository': Invocation of init method failed; nested exception is org.springframework.data.mapping.model.MappingException: Could not lookup mapping metadata for domain class java.util.HashMap!
```

## 问题的优质回答：

先讲结论：
* 不能直接使用或者继承java集合类作为实体类
* Spring Data MongoDB 的 Repositories 不能实现上面的需求
* 使用 DBObject 类配合 MongoTemplate 才能实现

分析；
Spring Data Repositories 是MongoDB用在设计好的数据结构中的持久层，为了配合面向对象设计使用。Spring Data 在分析检查类的时候，会将集合和Map类型的类剔除出去。

Repository 的查询方法虽然也可以实现动态字段查询，但不是主流的用法。
```java
public interface EntryRepository extends MongoRepository<Entry, String> {
    @Query("{ ?0 : ?1 }")
    Entry findByDynamicField(String field, Object value);
}
```
这个方法不提供任何类型安全检查，只是简陋地为字段提供一个别名。

最好使用 DBObject 配合 MongoTemplate 的Query方法使用。
```java
List<DBObject> result = template.find(new Query(Criteria.where("your_dynamic_field").is(theQueryValue)), DBObject.class);
```
DBObject 能在不定义数据结构的情况下，直接映射MongoDB文档的结果。可以直接使用 DBObject 类结合 MongoTemplate 进行增删改查的操作。

## 我个人的尝试
应该是 Spring Data MongoDB 相关团队了解到了这个需求，目前的spring-data-mongodb 3.1.6 版本中，MongoTemplate 类的insert和save方法都可以直接操作Map数据，甚至对于任意实体类都能随意操作，只要指定 collection 名称就行。
```java
HashMap object = new HashMap();
object.put("file_name", "object");
object.put("md5", "1827391");
HashMap aa = template.save(object, "file");

Student student = new Student("小明", "希望小学");
Student bb = template.save(student, "file");
```
以上代码均能正常执行，数据能准确存入数据库。