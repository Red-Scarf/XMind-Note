# Redis 进阶
# redis 订阅
redis 发布订阅系统, 可以接收任意频率的广播
* subscribe c1 c2 c3, 订阅c1, c2, c3频道的消息
* publish c1 "hello", 在c1频道发送消息
* psubscribe c*, 模式匹配订阅, 此时可以接收所有c开头的频道的消息
> 由于网络在传输过程中可能会遭遇断线等意外情况, 断线后需要进行重连, 然而这会导致断线期间的数据丢失。
# redis 事务
## 事务执行和清除
使用 `multi`, 开启一个事务, 开启之后可以发送若干个命令, 但不会立即执行, 而是放在一个队列中。所有命令输入完成后, 用 `exec` 命令可以发起执行, 也可以用 `discard` 清空队列。

## 事务异常
redis中事务的异常情况总的来说分为两类：
1. 进入队列之前就能发现的错误, 比如命令输错；
2. 执行EXEC之后才能发现的错误, 比如给一个非数字字符加1；

那么对于这两种不同的异常, redis中有不同的处理策略。对于第一种错误, 服务器会对命令入队失败的情况进行记录, 并在客户端调用 EXEC 命令时, 拒绝执行并自动放弃这个事务
```
127.0.0.1:6379> MULTI
OK
127.0.0.1:6379> set kv1 v1
QUEUED
127.0.0.1:6379> set k2 v2
QUEUED
127.0.0.1:6379> set k3 v3 3 3
QUEUED
127.0.0.1:6379> set k4 v4
QUEUED
127.0.0.1:6379> EXEC
1) OK
2) OK
3) (error) ERR syntax error
4) OK
127.0.0.1:6379> keys *
1) "k4"
2) "k2"
3) "kv1"
```

而对于第二种情况, redis并没有对它们进行特别处理,  即使事务中有某个/某些命令在执行时产生了错误,  事务中的其他命令仍然会继续执行。
```
127.0.0.1:6379> MULTI
OK
127.0.0.1:6379> set k1 vv
QUEUED
127.0.0.1:6379> INCR k1
QUEUED
127.0.0.1:6379> EXEC
1) OK
2) (error) ERR value is not an integer or out of range
127.0.0.1:6379> GET k1
"vv"
```

不同于关系型数据库, redis中的事务出错时没有回滚, 对此, 官方的解释如下：

> Redis 命令只会因为错误的语法而失败（并且这些问题不能在入队时发现）, 或是命令用在了错误类型的键上面：这也就是说, 从实用性的角度来说, 失败的命令是由编程错误造成的, 而这些错误应该在开发的过程中被发现, 而不应该出现在生产环境中。因为不需要对回滚进行支持, 所以 Redis 的内部可以保持简单且快速。

## watch 命令
事务中的 watch 命令可以用来监控一个 key, 通过这种监控, 我们可以为 redis 事务提供(CAS)行为。 如果有至少一个被 watch 监视的键在 exec 执行之前被修改了, 那么整个事务都会被取消, exec 返回 nil-reply 来表示事务已经失败。

通过unwatch命令, 可以取消对一个key的监控

# 持久化
redis 持久化有两种方式, 快照持久化和 AOF, 在项目中我们可以根据实际情况选择合适的持久化方式, 也可以不用持久化, 这关键看我们的redis在项目中扮演了什么样的角色。

## 快照持久化

通过拍摄快照的方式实现数据的持久化: redis 可以在某个时间点上对内存中的数据创建一个副本文件, 副本文件中的数据在 redis 重启时会被自动加载, 我们也可以将副本文件拷贝到其他地方一样可以使用。

redis 中的快照持久化默认是开启的, redis.conf 中相关配置主要有如下几项：
```
save 900 1
save 300 10
save 60 10000
stop-writes-on-bgsave-error yes
rdbcompression yes
dbfilename dump.rdb
dir ./
```
前面三个 save 相关的选项表示备份的频率, 分别表示900秒内至少一个键被更改则进行快照, 300秒内至少10个键被更改则进行快照, 60秒内至少10000个键被更改则进行快照。

`stop-writes-on-bgsave-error` 表示在快照创建出错后, 是否继续执行写命令。

`rdbcompression` 则表示是否对快照文件进行压缩。

`dbfilename` 表示生成的快照文件的名字。

`dir` 则表示生成的快照文件的位置。

redis 快照会保存在 redis 的安装目录中, 文件名为 `dump.rdb`, redis 重启之后, 会通过该文件恢复之前的数据。

## 快照持久化流程
1. 在redis运行过程中, 我们可以向 redis 发送一条 save 命令来创建一个快照, save 是一个阻塞命令, redis 在接收到 save 命令之后, 开始执行备份操作之后, 在备份操作执行完毕之前, 将不再处理其他请求, 其他请求将被挂起, 因此这个命令我们用的不多。
2. 在 redis  运行过程中, 我们也可以发送一条 bgsave 命令来创建一个快照, 不同于 save 命令, bgsave 命令会 fork 一个子进程, 然后这个子进程负责执行将快照写入硬盘, 而父进程则继续处理客户端发来的请求, 这样就不会导致客户端命令阻塞了。
3. 如果我们在 redis.conf 中配置了如下选项, 那么当条件满足时, 比如900秒内有一个 key 被操作了, 那么 redis 就会自动触发 bgsava 命令进行备份。
```
save 900 1
save 300 10
save 60 10000
```
4. 还有一种情况也会触发 save 命令, 那就是我们执行 shutdown 命令时, 当我们用 shutdown 命令关闭 redis 时, 此时也会执行一个 save 命令进行备份操作, 并在备份操作完成后将服务器关闭。
5. 还有一种特殊情况也会触发 bgsave 命令, 就是在主从备份的时候。当从机连接上主机后, 会发送一条 sync 命令来开始一次复制操作, 此时主机会开始一次 bgsave 操作, 并在 bgsave 操作结束后向从机发送快照数据实现数据同步。

## 快照持久化的缺点
快照持久化有一些缺点, 比如 save 命令会发生阻塞, bgsave 虽然不会发生阻塞, 但是 fork 一个子进程又要耗费资源, 在一些极端情况下, fork 子进程的时间甚至超过数据备份的时间。定期的持久化也会让我们存在数据丢失的风险, 最坏的情况我们可能丢失掉最近一次备份到当下的数据, 具体丢失多久的数据, 要看我们项目的承受能力, 我们可以根据项目的承受能力配饰 save 参数。

## AOF 持久化
与快照持久化不同, AOF 持久化是将被执行的命令写到 aof 文件末尾, 在恢复时只需要从头到尾执行一遍写命令即可恢复数据, AOF 在 redis 中默认也是没有开启的, 需要我们手动开启。

打开 redis.conf 配置文件, 修改 appendonly 属性值为 yes 。
```
appendonly yes
```
另外几个和 AOF 相关的属性如下：
```
appendfilename "appendonly.aof"
# appendfsync always
appendfsync everysec
# appendfsync no
no-appendfsync-on-rewrite no
auto-aof-rewrite-percentage 100
auto-aof-rewrite-min-size 64mb
```
这几个属性的含义分别如下：

1. appendfilename 表示生成的 AOF 备份文件的文件名。
2. appendfsync 表示备份的时机, always 表示每执行一个命令就备份一次, everysec 表示每秒备份一次, no 表示将备份时机交给操作系统。
3. no-appendfsync-on-rewrite 表示在对 aof 文件进行压缩时, 是否执行同步操作。
4. 最后两行配置表示 AOF 文件的压缩时机。

同时为了避免快照备份的影响, 我们将快照备份关闭, 关闭方式如下：
```
save ""
# save 900 1
# save 300 10
# save 60 10000
```

## AOF 备份的几个关键点
1. appendfsync 的取值一共有三种, 项目中首选 everysec, always 选项会严重降低 redis 性能。
2. 使用 everysec, 最坏的情况下我们可能丢失1秒的数据。

## AOF 文件的重写与压缩
随着系统的运行, AOF 的文件会越来越大, 甚至把整个电脑的硬盘填满, AOF 文件的重写与压缩机制可以在一定程度上缓解这个问题。当 AOF 的备份文件过大时, 我们可以向 redis 发送一条 bgrewriteaof 命令进行文件重写。
```
127.0.0.1:6379> BGREWRITEAOF
Background append only file rewriting started
(0.71s)
```
bgrewriteaof 的执行原理和 bgsave 的原理一致。

bgrewriteaof 也可以自动执行, 自动执行时间则依赖于 auto-aof-rewrite-percentage 和 auto-aof-rewrite-min-size 配置。

* auto-aof-rewrite-percentage 100 表示当目前aof文件大小超过上一次重写时的 AOF 文件大小的百分之多少时会再次进行重写, 如果之前没有重写, 则以启动时的 AOF 文件大小为依据。
* auto-aof-rewrite-min-size 64mb 表示 AOF 文件的大小至少要大于 64M。

## 实践
1. 如果 redis 只做缓存服务器, 那么可以不使用任何持久化方式。
2. 同时开启两种持久化方式, 在这种情况下,当 redis 重启的时候会优先载入 AOF 文件来恢复原始的数据, 因为在通常情况下AOF文件保存的数据集要比 RDB 文件保存的数据集要完整。RDB 的数据不完整时, 同时使用两者时服务器重启也只会找 AOF 文件。
3. 建议不要只使用 AOF, 因为 RDB 更适合用于备份数据库( AOF 在不断变化不好备份),  快速重启, 而且不会有 AOF 可能潜在的 bug, 留着作为一个万一的手段。
4. 因为 RDB文件只用作后备用途, 建议只在 slave 上持久化 RDB 文件, 而且只要15分钟备份一次就够了, 只保留 save 900 1 这条规则。
5. 如果 Enalbe AOF, 好处是在最恶劣情况下也只会丢失不超过两秒数据, 启动脚本较简单只 load 自己的 AOF 文件就可以了。代价一是带来了持续的 IO, 二是 AOF rewrite 的最后将 rewrite 过程中产生的新数据写到新文件造成的阻塞几乎是不可避免的。只要硬盘许可, 应该尽量减少 AOF rewrite 的频率, AOF 重写的基础大小默认值 64M 太小了, 可以设到 5G 以上。默认超过原大小100%大小时重写可以改到适当的数值。
6. 如果不 Enable AOF , 仅靠 Master-Slave Replication 实现高可用性也可以。能省掉一大笔 IO 也减少了 rewrite 时带来的系统波动。代价是如果 Master/Slave 同时倒掉, 会丢失十几分钟的数据, 启动脚本也要比较两个 Master/Slave 中的 RDB 文件, 载入较新的那个。