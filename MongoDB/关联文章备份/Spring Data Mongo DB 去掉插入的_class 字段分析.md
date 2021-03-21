> 本文由 [简悦 SimpRead](http://ksria.com/simpread/) 转码， 原文地址 [blog.csdn.net](https://blog.csdn.net/asahinokawa/article/details/83894670)

大致的框架是从网上找来的资源。但是遇到了两个问题：

1.  运行代码后，MongoDB 数据库没有收到改变。  
    想起了在 yaml 中配置的 mongodb 参数，那些参数，data 的上一层是 spring，如下：  
    ![](img\20181109143046420.png)
    
	spring boot 在加载这些数据时，会得到一个`MongoProperties`。按住 Ctrl，然后点在那些值上，然后点进去后，就出来了。  
    ![](img\20181109143529561.png)
    该类的属性如下：  
    ![](img\20181109143746128.png)
    所以 Spring Boot 的加载过程中，会产生这样一个 bean，通过注入的方式，拿到该类，便可以拿到相应的 mongo db 配置。再通过一定的分析，将配置信息传给自定义的 MongoTemplate。
    
2.  其中使用 @Deprecated 方法。  
    这里换成了其中的没有 @Deprecated 的构造方法。
    

先给出可以忽略掉_class 字段的配置如下：

```
@Configuration
public class SpringMongoConfig {

  @Autowired
  public MongoProperties mongoProperties;

  public @Bean MongoDbFactory mongoDbFactory() throws Exception {
    // MongoClientURI uri = new MongoClientURI("mongodb://"+mongoProperties.getUsername()+":"+new String(mongoProperties.getPassword())+"@"+mongoProperties.getHost()+"/"+mongoProperties.getDatabase());
    return new SimpleMongoDbFactory(mongoProperties.createMongoClient(null, null), mongoProperties.getDatabase());
  }

  public @Bean MongoTemplate mongoTemplate() throws Exception {
    //remove _class
    MappingMongoConverter converter = new MappingMongoConverter(new DefaultDbRefResolver(mongoDbFactory()), new MongoMappingContext());
    converter.setTypeMapper(new DefaultMongoTypeMapper(null));

    return new MongoTemplate(mongoDbFactory(), converter);
  }
}
```

从源代码去分析这个过程
-----------

从 save() 开始：

```
// 从MongoTemplate的save方法开始
public void save(Object objectToSave) {
	Assert.notNull(objectToSave, "Object to save must not be null!");
	save(objectToSave, determineEntityCollectionName(objectToSave));
}
// 获取到集合的名字后，再存储
public void save(Object objectToSave, String collectionName) {

	Assert.notNull(objectToSave, "Object to save must not be null!");
	Assert.hasText(collectionName, "Collection name must not be null or empty!");

	MongoPersistentEntity<?> mongoPersistentEntity = getPersistentEntity(objectToSave.getClass());

	// No optimistic locking -> simple save
	if (mongoPersistentEntity == null || !mongoPersistentEntity.hasVersionProperty()) {
		doSave(collectionName, objectToSave, this.mongoConverter);
		return;
	}

	doSaveVersioned(objectToSave, mongoPersistentEntity, collectionName);
}
// 存储的主要步骤
protected <T> void doSave(String collectionName, T objectToSave, MongoWriter<T> writer) {

	maybeEmitEvent(new BeforeConvertEvent<T>(objectToSave, collectionName));
	assertUpdateableIdIfNotSet(objectToSave);
	// 加上_class的语句在这里
	DBObject dbDoc = toDbObject(objectToSave, writer);
	// 执行词语后，debug显示的数据如下图
	maybeEmitEvent(new BeforeSaveEvent<T>(objectToSave, dbDoc, collectionName));
	Object id = saveDBObject(collectionName, dbDoc, objectToSave.getClass());

	populateIdIfNecessary(objectToSave, id);
	maybeEmitEvent(new AfterSaveEvent<T>(objectToSave, dbDoc, collectionName));
}
```

执行`toDbObject(objectToSave, writer);`后，debug 显示的数据如下：  
![](img\20181109112035813.png)
所以继续`toDbObject(objectToSave, writer);`里面走：  
![](img\20181109113029400.png)
所以进入`MappingMongoConverter`的`write(final Object obj, final DBObject dbo)`方法：

```
public void write(final Object obj, final DBObject dbo) {

	if (null == obj) {
		return;
	}

	Class<?> entityType = obj.getClass();
	boolean handledByCustomConverter = conversions.getCustomWriteTarget(entityType, DBObject.class) != null;
	TypeInformation<? extends Object> type = ClassTypeInformation.from(entityType);
	// typeMapper在MappingMongoConverter的构造函数中初始化
	if (!handledByCustomConverter && !(dbo instanceof BasicDBList)) {
		typeMapper.writeType(type, dbo);
	}

	Object target = obj instanceof LazyLoadingProxy ? ((LazyLoadingProxy) obj).getTarget() : obj;

	writeInternal(target, dbo, type);
}
```

与 typeMapper 初始化相关的代码如下：  
![](img\2018110911415651.png)
因此，打开`DefaultMongoTypeMapper`，找到`writeType`方法，但是发现并没有，所以从它的父类`DefaultTypeMapper<DBObject>`中找。  
![](img\20181109123649129.png)
再看 accessor.writeTypeTo()。这里的 accessor 在该类初始化的构造器中就已经被初始化，所以我们可以从继承该类的子类 DefaultMongoTypeMapper 中找到该类的实现，这里的 accessor 就是 DefaultMongoTypeMapper 的一个静态内部类。 
![](img\20181109125851328.png) 
这个时候，再回想一下之前的修改，有一种豁然开朗的感觉，哈哈。