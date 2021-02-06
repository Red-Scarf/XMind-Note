## Redis 入门
### Redis 数据类型
redis 使用键值对形式存储数据, 其中值支持五种数据类型。
* STRING, 二进制安全, 可以包含任何数据, 如序列化的对象或者jpg图像, 字符串大小的上限512M。
* LIST, 简单的字符串列表, 按插入顺序排序, 支持头插尾插, 也支持头尾弹出元素。
* HASH, 类似 Java 的 Map, 键值对合集, 可以存对象。
* SET, STRING 类型的无序集合, 元素不可重复。
* ZSET, 和 SET 一样, 但每个元素都关联一个 double 类型的分数。

### 命令
* set k1 v1, 插入数据
* del k1, 删除数据, 返回 (integer) 1 表示成功
* dump k1, 序列化给定的 key, 返回序列化结果
* exists k1, k1 是否存在, (integer) 1 表示存在
* ttl k1, 查看k1 的有效期, (integer) -1 表示没设置,永久有效, (integer) -2 表示不存在/已过期
* expire k1 30, 设置过期时间为 30s
* persist k1, 移除过期时间
* pexpire k1 3000, 设置过期时间, 单位毫秒
* pttl k1, 返回过期时间, 单位毫秒
* keys *, 获取满足给定模式的所有key, * 表示所有, 也可以使用正则

### STRING 类型
* append k1 hello, 如果 k1 存在, 会直接在 k1 的值后面附加 hello, 如果不存在, 创建一个空字符串后再附加
* decr k1, 对 k1 的值减一, 如果不是数字, 报错
* decrby k1 4, 对 k1 的值加4, 可以指定加减大小
* get k1, 获取对应 key 的 value, 不存在返回 nil
* getrange k1 0 2, 返回 k1 字符串的子串, 子串由 start 和 end 决定, 下标负数表示倒数
* getset k1 aa, 获取 key 的值, 并且设置新的值
* incr k1, 执行加一操作, 如果值不存在, 初始化值为0, 值不是数字, 报错
* incrby k1 2, 指定值增加2
* incrbyfloat k1 2.2, 指定增加浮点数
* mget 和 mset, 批量获取值和设置值
```
mset k1 v1 k2 v2 k3 v3
mget k1 k2 k3
```
* setex k1 30 hello, 同时设置和过期时间
* psetex k1 3000 hello, 和 setex 一样, 但时间单位为毫秒
* setnx k1 hello, 使用 set 命令时, 会覆盖旧有的 k1 的值, 使用此命令, 如果 k1 存在, 不作操作
* msetnx, 批量执行 setnx, 如果有一个k1 存在, 则所有的都不会执行
* setrange k1 5 hello, 覆盖已存在的key 的value, 如果原本的value长度不够, 用0补足
* strlen k1, 返回字符串长度

### STRING 的 BIT 操作
redis 存字符串时, 是先将字符转成ASCII码, 然后再转成二进制, bit 相关的命令就是针对这个二进制值进行操作, 下标指的是整个字符串转成二进制之后, 二进制值的下标。

可以用bit操作实现位图法或者布隆过滤器。
* getbit k1 2, 获取下标为2的字符的bit值
* setbit k1 2 1, 设置对应下标的bit值
* bitcount k1, 统计二进制数据中的1的个数
* bitop and k1 k2 k3, 对一个或多个二进制串执行并(and)、或(or)、异或(xor)、非(not)运算。
* bitops k1 1, 获取二进制串中, 第一个1的位置, 也可以获取第一个0的位置

### 列表
* lpush k1 v1 v3 v2, 从左往右按顺序插入k1的列头
* lrange k1 0 -1, 获取列表k1中指定范围的值, 负数表示倒数
* rpush k1 1 2 3, 从右往左依次插入列表
* rpop k2, 弹出列表的尾元素
* lpop k2, 弹出列表的头元素
* lindex k1 2, 返回指定下标的元素
* ltrim k1 1 4, 列表只保留指定区间内的元素
* blpop k1 10, 阻塞式弹出命令, 功能与lpop一致, 但需要指定等待时间

### 集合
* sadd k1 v1 v2 v3, 添加值到集合k1中
* srem k1 v1, 移出指定元素, 不存在该元素则忽略, 空集合返回0
* sismember k1 v1, 判断元素是否在集合汇总
* scard k1, 返回集合元素的值
* smembers k1, 返回所有的元素
* srandmember, 仅需我们提供key参数,它就会随机返回key集合中的一个元素，从Redis2.6开始,该命令也可以接受一个可选的count参数,如果count是整数且小于元素的个数，则返回count个随机元素,如果count是整数且大于集合中元素的个数时,则返回集合中的所有元素,当count是负数,则会返回一个包含count的绝对值的个数元素的数组，如果count的绝对值大于元素的个数,则返回的结果集里会出现一个元素出现多次的情况。
* spop, 用法和 srandmember 类似，不同的是， spop 每次选择一个随机的元素之后，该元素会出栈
* smove k1 k2 v1, 将v1从k1转移到k2
* sdiff k1 k2, 返回两个集合的差集
* sdiffstore k3 k1 k2, 将k1和k2的差集保存在k3中
* sinter k1 k2, 返回 k1 和 k2 的交集
* sinterstore k3 k1 k2, 求k1 k2 的交集存在k3中
* sunion k1 k2, 返回两个集合的并集
* sunionstore k3 k1 k2, 求k1k2并集, 存在k3

### 哈希集合
redis 相应 key 中的键值对
* hset k1 h1 v1, 在k1中, 设置h1集合, 存入v1值
* hget k1 h1, 获取值
* hmset k1 h1 v1 h2 v2, 批量设置
* hmget k1 h1 h2, 批量获取
* hdel k1 h1 h2, 删除, 不存在则忽略
* hsetnx k1 h2 1, h3 下不存在值则设置值, 存在值则忽略
* hvals k2, 获取k2中所有的值
* hkeys k2, 获取k2中所有的键
* hgetall k2, 获取k2中的所有键值对, 每个字段后面是他的值
* hexists k2 h2, 判断h2是否存在
* hincrby k2 h1, 加一, 不存在则初始化为0, 支持64位有符号整数
* hincrbyfloat, 与 hincrby 用法一致, 操作的数字是浮点型
* hlen k1, 返回元素数量
* hstrlen k2 h2, 返回长度

### 有序集合
有序集合类似Sets,但是每个字符串元素都关联到一个叫score浮动数值。里面的元素总是通过score进行着排序，因此它是可以检索的一系列元素。

* zadd k1 60 v1, 添加值到k1中, 指定值的分数, 更新排序
* zscore k1 v1, 返回值的分数
* zrange k1 0 3, 返回0到3的值, 末尾加上 withscores 可以附带返回分数
* zrevrange k1 0 3, 用法和 zrange 类似, 但倒序
* zcard k1, 返回元素个数
* zcount k1 30 60, 返回分数区间内的元素个数
* zrangebyscore k2 30 60, 输出分数范围内的元素, withscores 将元素的分数带出来
* zrank k1 v1, 返回v1的排名
* zrevrank k1 v1, 返回v1的逆序(从大到小)排名
* zincrby k1 3 v1, 为v1的分数加3, 元素不存在则创建元素初始化分数为0再加3
* zinterstore k4 2 k2 k3, 指定2个集合, 计算这两个几个的交集, 存在k4中, 元素的分数相加
* zrem k2 v1, 从集合中弹出v1
* zlexcount k2 - +, 计算有序集合中指定成员之间的成员数量, - 表示最小值, + 表示最大值, 如果要指定元素, 要在元素的左边加上[, `zlexcount k2 [v2 [v4`
* zrangebylex - +, 返回指定元素区间内的元素, 指定元素的方法和 zlexcount 一致

