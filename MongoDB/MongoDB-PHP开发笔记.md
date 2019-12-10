# MongoDB学习笔记
# PHP相关
## 基础拓展库
> 本次没有使用，所以不赘述
## lib拓展库
MongoDB是一种分布式文件存储系统，可以存文件和“文档”。“文档”即非关系型数据库，数据库除了主键`_id`外，其他的属性均为不定项，当新增一条记录时，根据插入的记录的键值对设置属性。
### 安装
本次使用的是lib拓展库，使用前要注意环境的一致，PHP要开启MongoDB扩展。
lib可以使用composer安装，也可以直接下载相应的文件，具体看官方文档。[MongoDB 官方文档](https://docs.mongodb.com/php-library/current/tutorial/install-php-library/ "MongoDB 官方文档")
### 连接
安装成功后，直接引入vender文件夹下的`autoload.php`文件即可。
连接数据库，首先要明确ip、端口、用户名、密码，例子中第二个`admin`指的是去数据库的基础表admin中对比用户名密码正误。
```
$hot_Client = new MongoDB\Client("mongodb://admin:123456@127.0.0.1:27018/admin");
```
连接MongoDB数据库之后，还需要选择使用哪个集合(表)或者哪个数据桶，在PHP的这个lib中，client返回的是一个对象，所有的集合和存储桶都是对象的属性，所以选择集合或者数据桶时，直接返回对应的属性即可。
```
$this->all_Collection = $this->all_Client->file_db->upload_list_copy2; // 测试数据
$this->all_GridFsCollection = $this->all_Client->file_db->selectGridFSBucket(); // 数据桶
```
### collection
MongoDB作为非关系型数据库，没有传统的关系型数据库那种固定的表属性，除了`_id`属性外，其他的属性都无法设置必填，而且所有的属性都是可以随时新增和置空的。
> 不论是文档集合还是GFs，其中的主键都是`MongoDB\BSON\ObjectId`类型，所以查询或者插入、更新的时候，必须使用` MongoDB\BSON\ObjectId`对象才能匹配。

#### 查询
查询语句最为基础也最为复杂，最简单的全局查询直接使用find()方法即可。
```
$result = $this->all_Collection->find();
```
`find()`查询的结果为`MongoDB\Driver\Cursor`对象，直接输出该对象显示的是查询时的前提条件信息。该对象可以使用`foreach`迭代输出查询结果，或者使用`->toArray()`

##### **查询一条**
查询一条使用`findOne()`方法，返回的结果直接就是第一个文档结果，相当于
```
$this->all_Collection->find()->toArray()[0];
```

##### **查询多条**
多条查询使用`find()`，在多条查询出展开讲如何实现调价查询。
查询时的条件均使用数组形式构造，使用`find()`方法时，参数一表示查询条件，参数二表示查询时配置，具体格式如下
```
// 查出file_name字段等于moive_1的文档，限制只查10条记录
$this->all_Collection->find(
  ['file_name' => 'moive_1'],
  [
    'limit' => 10
  ]
);
```
查询时，条件符号如下：
* $gt 大于 >
* $gte 大于等于 >=
* $lt 小于 <
* $lte 小于等于 <=
* $ne 不等于 !=
* $eq 等于 =
> 使用PHP进行比较时，日期如果是存string格式，可以直接比较，MongoDB会直接对比

使用多条件查询时，用`$or`和`$and`时，必须是一个数组
```
$collResult = $this->hot_Collection->find(
  [
    '$and' => [
      'aa' => '123',
      'date' => ['$gt' => '2019-01-01']
    ]
  ]
)
```

参数二的常用配置项如下：
* projection
  * 类型 	array|object
  * 可选项
  * 设置返回的结果里包含哪些字段，`_id`字段默认返回
```
$result = $collection->find(
  [],
  [
    'projection' => [
      'name' => 1,
      '_id' => 0, // _id字段必须显式设置为0才能在返回的结果中排除
    ],
  ]
)
```
* skip
  * 类型 integer
  * 可选项
  * 设置要跳过的文档数，默认为0
* limit
  * 类型 integer
  * 可选项
  * 设置要返回的最大文档数。如果未指定，则默认为无限制。限制为0等于不设置限制。
```
// 利用skip和limit实现分页
$result = $collection->find(
  [],
  [
    'limit' => 10,
    'skip' => 10
  ]
)
```

##### **正则查询**
使用`MongoDB\BSON\Regex`类或者`$Regex`运算符进行正则匹配
```
// city字段以garden开头，state字段等于TX
$cursor = $collection->find([
  'city' => new MongoDB\BSON\Regex('^garden', 'i'),
  'state' => 'TX',
]);
```

##### **带聚合的复杂查询**
使用`aggregate()`方法进行复杂查询，参数一是查询的各种条件，参数二是配置项。一般使用参数一即可完成基本功能。
参数一常用项目如下：
* $match
  * 自己独立一个数组元素
  * 相当于SQL中的`where`和`having`
  * 主要的查询条件，在参数一数组中可以多次出现
```
[
  '$match' => [
    'isHot' => false,
    'date' => ['$gte' => $date],
  ]
],
```
* $group
  * 自己独立一个数组元素
  * 相当于`group by`
  * 对数据字段进行聚合使用格式，使用时，使用`_id`字段保存所有的聚合字段，`'file_id' => '$file_id'`表示结果集中的`file_id`字段为文档中的`file_id`字段。
```
[
  '$group' => [
    '_id' => [
      'file_id' => '$file_id'
    ],
    'count' => ['$sum' => 1]
  ]
],
```
* $project
  * 自己独立一个数组元素
  * 相当于`select`
  * 设置结果集返回的字段，默认返回`_id`，必须显式设置才能排除`_id`字段
```
[
  '$project' => [
    '_id' => 0,
  ]
],
```
* $skip
  * 自己独立
  * 跳过文档的数量
* $limit
  * 自己独立
  * 结果集文档最大数量
* $sum
  * 不独立一个数字元素，当做方法使用
  * 相当于`sum()`，可以对某字段进行累加
  * 允许使用sum => 1，实现`count()`的功能
```
// 计算聚合后每个file_id的数量，区别名count
[
  '$group' => [
    '_id' => [
      'file_id' => '$file_id'
    ],
    'count' => ['$sum' => 1]
  ]
]
```

#### 删除
删除的参数类比`find()`方法，结果返回的是`MongoDB\DeleteResult`类，可以使用`getDeletedCount()`方法查看删除的条数。
##### **删除一条**
使用`deleteOne()`方法，`getDeletedCount()`返回1表示删除成功，返回0表示没有删除任何文档。一般情况下，会删除匹配到的第一条文档记录。
```
$deleteResult = $collection->deleteOne(['state' => 'ny']);
printf("Deleted %d document(s)\n", $deleteResult->getDeletedCount()); // 输出删除了几条
```
##### **删除多条**
使用`deleteMany()`方法，`getDeletedCount()`会返回实际删除的条目。
```
$deleteResult = $collection->deleteMany(['state' => 'ny']);
printf("Deleted %d document(s)\n", $deleteResult->getDeletedCount()); // 输出删除了几条
```

#### 插入
根据条件插入文档，返回`MongoDB\InsertOneResult`类，`getInsertedCount()`返回插入成功的条数，`getInsertedId()`返回插入后生成的id，为`MongoDB\BSON\ObjectId`对象，因为id是驱动程序生成的，所以不论是否写入成功，都能返回id
##### **插入一条**
使用`insertOne()`方法，参数是一个一维数组，`getInsertedId()`返回1表示插入成功，返回0表示没有插入。
```
$insertOneResult = $collection->insertOne([
  'username' => 'admin',
  'email' => 'admin@example.com',
  'name' => 'Admin User',
]);
```
##### **插入多条**
使用`insertMany()`方法，参数是一个二维数组，`getInsertedId()`返回值大于0表示插入成功，返回0表示没有插入。
```
$insertManyResult = $collection->insertMany([
  [
    'username' => 'admin',
    'email' => 'admin@example.com',
    'name' => 'Admin User',
  ],
  [
    'username' => 'test',
    'email' => 'test@example.com',
    'name' => 'Test User',
  ],
]);
```

#### 修改
使用的方式类似插入，但更新方法有两个必需的参数：标识要更新的一个或多个文档的查询筛选器(格式类比find())，以及指定要执行哪些更新的更新文档，使用`$set`字段标识要修改的东西。返回一个`MongoDB\UpdateResult`对象。
* getMatchedCount() 匹配的文档数
* getModifiedCount() 返回已插入文档的ID
* getUpsertedCount() 返回追加插入的文档数。只有在确认写入时才应调用此方法。
* getUpsertedId() 返回已修改的文档数。只有在确认写入时才应调用此方法。

##### **修改一条**
```
$updateResult = $collection->updateOne(
  ['state' => 'ny'],
  ['$set' => ['country' => 'us']]
);
printf("Matched %d document(s)\n", $updateResult->getMatchedCount()); // 匹配到的条数
printf("Modified %d document(s)\n", $updateResult->getModifiedCount()); // 修改成功的条数
```
##### **修改多条**
如果更新操作没有导致文档更改，例如将字段值设置为当前值，则修改的文档数可以小于匹配的文档数。
```
$updateResult = $collection->updateMany(
  ['state' => 'ny'],
  ['$set' => ['country' => 'us']]
);
```
##### **修改或插入**
当没有与指定筛选器匹配的文档时，该操作将创建一个新文档并将其插入。如果存在匹配的文档，则操作将修改或替换匹配的一个或多个文档。当文档被追加插入时，可以通过`getUpsertedId()`访问该ID。
```
$updateResult = $collection->updateOne(
  ['name' => 'Bob'],
  ['$set' => ['state' => 'ny']],
  ['upsert' => true]
);
// 获取插入的ID
$upsertedDocument = $collection->findOne([
    '_id' => $updateResult->getUpsertedId(),
]);
```
##### **替换**
替换操作类似于更新操作，但替换操作不会更新文档以包含新字段或新字段值，而是将整个文档替换为新文档，但保留原始文档的id值。使用`replaceOne()`方法，返回的也是`MongoDB\UpdateResult`对象。
```
$updateResult = $collection->replaceOne(
  ['name' => 'Bob'],
  ['name' => 'Robert', 'state' => 'ca']
);
```

### GridFS存储桶
创建一个新的存储桶
#### 查询信息
#### 上传文件
要使用可写流将文件上载到GridFS，可以打开一个流并直接写入它，也可以将另一个可读流的全部内容一次性写入GridFS。
```
// 打开上传流
$stream = $bucket->openUploadStream('my-file.txt');
$contents = file_get_contents('/path/to/my-file.txt');
fwrite($stream, $contents);
fclose($stream);
```
```
// 在一次调用中上载可读流的全部内容
$bucket = (new MongoDB\Client)->test->selectGridFSBucket();
$file = fopen('/path/to/my-file.txt', 'rb');
$bucket->uploadFromStream('my-file.txt', $file);
```
#### 下载文件
要使用可读流从GridFS下载文件，可以打开一个流并直接从中读取，也可以一次下载整个文件，一般是使用读取文件流的形式。
```
// 打开下载流并从中读取
$fileId = new MongoDB\BSON\ObjectId;
$bucket = (new MongoDB\Client)->test->selectGridFSBucket();
$stream = $bucket->openDownloadStream($fileId);
$contents = stream_get_contents($stream);
```
> 在swoole中，可以使用`$response->write($contents);`方法返回给前端下载。
```
// 一次下载文件并将其写入可写流
$fileId = new MongoDB\BSON\ObjectId;
$bucket = (new MongoDB\Client)->test->selectGridFSBucket();
$file = fopen('/path/to/my-output-file.txt', 'wb');
$bucket->downloadToStream($fileId, $file);
```
#### **删除文件**
通过`_id`删除文件
```
$fileId = new MongoDB\BSON\ObjectId;
$bucket = (new MongoDB\Client)->test->selectGridFSBucket();
$bucket->delete($fileId);
```
### 安装
### 安装