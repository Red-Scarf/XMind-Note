# 解决问题
## 去掉保存数据时默认字段 _class
在用springboot写MongoDB的demo时，发现存数据的时候总是会存在一个多出来的字段`_class`。多方查证下才知道，这个字段主要是为了在将Mongo的数据还原到Java的时候能找到对应的类。

但是对我这个项目而言，mongodb虽然是nosql，但一般使用还是用类比sql的方式来操作，一个表存的就是一个实体。所以我就需要将这个字段删去，也是为了能够更好地兼容其他语言或环境。

下面是配置的主要代码

```java
@Configuration
public class MongoConfiguration {
    // 通过自动注入来获取默认的配置类
    @Autowired
    private MongoDatabaseFactorySupport mongoDatabaseFactory;
    @Autowired
    private MappingMongoConverter mappingMongoConverter;
    @Bean
    public MongoTemplate mongoTemplate() {
        mappingMongoConverter.setTypeMapper(new DefaultMongoTypeMapper(null));
        MongoTemplate mongoTemplate = new MongoTemplate(mongoDatabaseFactory, mappingMongoConverter);
        return mongoTemplate;
    }
}
```

具体的解决思路 [Spring Data Mongo DB去掉插入的_class字段分析](https://blog.csdn.net/asahinokawa/article/details/83894670) 这篇文章中有详细的思路。Stack Overflow 的这个问答中也有大致的讲解 [Spring data MongoDb: MappingMongoConverter remove _class](https://stackoverflow.com/questions/6810488/spring-data-mongodb-mappingmongoconverter-remove-class/)。

虽然在spring-data-mongodb 3.1.6中，上面两个解答中的代码已经有很多的变化，`MongoDbFactory`类已经被废弃，`SimpleMongoDbFactory`类更是已经删除，但是其中解决问题的思路是没问题的。

解决的过程磕磕绊绊，翻了一天的源码，最后通过idea Alt+Insert 快捷键发现能写Autowired，找到其中的`mongoDatabaseFactory`和`mappingMongoConverter`类才算解决。
