# MySQL 知识点

## 基础
登录
```sql
mysql -h 127.0.0.1 -u 用户名 -p
mysql -D 所选择的数据库名 -h 主机名 -u 用户名 -p
mysql> exit # 退出 使用 “quit;” 或 “\q;” 一样的效果
mysql> status;  # 显示当前mysql的version的各种信息
mysql> select version(); # 显示当前mysql的version信息
mysql> show global variables like 'port'; # 查看MySQL端口号
```

创建库
```sql
-- 创建一个名为 samp_db 的数据库，数据库字符编码指定为 gbk
create database samp_db character set utf8;
drop database samp_db; -- 删除 库名为samp_db的库
show databases;        -- 显示数据库列表。
use samp_db;     -- 选择创建的数据库samp_db
show tables;     -- 显示samp_db下面所有的表名字
describe 表名;    -- 显示数据表的结构
delete from 表名; -- 清空表中记录
```

 > 对于表的操作需要先进入库use 库名

创建数据库表
```sql
-- 如果数据库中存在user_accounts表，就把它从数据库中drop掉
DROP TABLE IF EXISTS `user_accounts`;
CREATE TABLE `user_accounts` (
  `id`             int(100) unsigned NOT NULL AUTO_INCREMENT primary key,
  `password`       varchar(32)       NOT NULL DEFAULT '' COMMENT '用户密码',
  `reset_password` tinyint(32)       NOT NULL DEFAULT 0 COMMENT '用户类型：0－不需要重置密码；1-需要重置密码',
  `mobile`         varchar(20)       NOT NULL DEFAULT '' COMMENT '手机',
  `create_at`      timestamp(6)      NOT NULL DEFAULT CURRENT_TIMESTAMP(6),
  `update_at`      timestamp(6)      NOT NULL DEFAULT CURRENT_TIMESTAMP(6) ON UPDATE CURRENT_TIMESTAMP(6),
  -- 创建唯一索引，不允许重复
  UNIQUE INDEX idx_user_mobile(`mobile`)
)
ENGINE=InnoDB DEFAULT CHARSET=utf8
COMMENT='用户表信息';
```

数据类型

* NULL：数据列可包含NULL值；
* NOT NULL：数据列不允许包含NULL值；
* DEFAULT：默认值；
* PRIMARY KEY：主键；
* AUTO_INCREMENT：自动递增，适用于整数类型；
* UNSIGNED：无符号(数值类型只能为正数)；
* CHARACTER SET name：指定一个字符集；
* COMMENT：对表或者字段说明；

SELECT

```sql
-- 表station取个别名叫s，表station中包含 字段id=13或者14 的，并且id不等于4的 查询出来，只显示id
SELECT s.id from station s WHERE id in (13,14) and id not in (4);

-- 从表 Persons 选取 LastName 列的数据
SELECT LastName FROM Persons

-- 从表 users 选取 id=3 的数据，并只拉一条数据(据说能优化性能)
SELECT * FROM users where id=3 limit 1

-- 结果集中会自动去重复数据
SELECT DISTINCT Company FROM Orders 
-- 表 Persons 字段 Id_P 等于 Orders 字段 Id_P 的值，
-- 结果集显示 Persons表的 LastName、FirstName字段，Orders表的OrderNo字段
SELECT p.LastName, p.FirstName, o.OrderNo FROM Persons p, Orders o WHERE p.Id_P = o.Id_P 

-- gbk 和 utf8 中英文混合排序最简单的办法 
-- ci是 case insensitive, 即 “大小写不敏感”
SELECT tag, COUNT(tag) from news GROUP BY tag order by convert(tag using gbk) collate gbk_chinese_ci;
SELECT tag, COUNT(tag) from news GROUP BY tag order by convert(tag using utf8) collate utf8_unicode_ci;
```

UPDATE
```sql
-- update语句设置字段值为另一个结果取出来的字段
update user set name = (select name from user1 where user1 .id = 1 )
where id = (select id from user2 where user2 .name='小苏');
-- 更新表 orders 中 id=1 的那一行数据更新它的 title 字段
UPDATE `orders` set title='这里是标题' WHERE id=1;
```

INSERT
```sql
-- 向表 Persons 插入一条字段 LastName = JSLite 字段 Address = shanghai
INSERT INTO Persons (LastName, Address) VALUES ('JSLite', 'shanghai');
-- 向表 meeting 插入 字段 a=1 和字段 b=2
INSERT INTO meeting SET a=1,b=2;
-- 
-- SQL实现将一个表的数据插入到另外一个表的代码
-- 如果只希望导入指定字段，可以用这种方法：
-- INSERT INTO 目标表 (字段1, 字段2, ...) SELECT 字段1, 字段2, ... FROM 来源表;
INSERT INTO orders (user_account_id, title) SELECT m.user_id, m.title FROM meeting m where m.id=1;

-- 向表 charger 插入一条数据，已存在就对表 charger 更新 `type`,`update_at` 字段；
INSERT INTO `charger` (`id`,`type`,`create_at`,`update_at`) VALUES (3,2,'2017-05-18 11:06:17','2017-05-18 11:06:17') ON DUPLICATE KEY UPDATE `id`=VALUES(`id`), `type`=VALUES(`type`), `update_at`=VALUES(`update_at`);
```

DELETE
```sql
-- 在不删除table_name表的情况下删除所有的行，清空表。
DELETE FROM table_name
-- 或者
DELETE * FROM table_name
-- 删除 Person表字段 LastName = 'JSLite' 
DELETE FROM Person WHERE LastName = 'JSLite' 
-- 删除 表meeting id 为2和3的两条数据
DELETE from meeting where id in (2,3);
```

UNION
```sql
-- 列出所有在中国表（Employees_China）和美国（Employees_USA）的不同的雇员名
SELECT E_Name FROM Employees_China UNION SELECT E_Name FROM Employees_USA

-- 列出 meeting 表中的 pic_url，
-- station 表中的 number_station 别名设置成 pic_url 避免字段不一样报错
-- 按更新时间排序
SELECT id,pic_url FROM meeting UNION ALL SELECT id,number_station AS pic_url FROM station  ORDER BY update_at;
-- 通过 UNION 语法同时查询了 products 表 和 comments 表的总记录数，并且按照 count 排序
SELECT 'product' AS type, count(*) as count FROM `products` union select 'comment' as type, count(*) as count FROM `comments` order by count;
```

JOIN
* JOIN: 如果表中有至少一个匹配，则返回行
* INNER JOIN:在表中存在至少一个匹配时，INNER JOIN 关键字返回行。
* LEFT JOIN: 即使右表中没有匹配，也从左表返回所有的行
* RIGHT JOIN: 即使左表中没有匹配，也从右表返回所有的行
* FULL JOIN: 只要其中一个表中存在匹配，就返回行(MySQL 是不支持的，通过 LEFT JOIN + UNION + RIGHT JOIN 的方式 来实现)
```sql
SELECT Persons.LastName, Persons.FirstName, Orders.OrderNo
FROM Persons
INNER JOIN Orders
ON Persons.Id_P = Orders.Id_P
ORDER BY Persons.LastName;
```

## 函数

### COUNT

### MAX

聚合函数

## 触发器
和外键关联一样，少用触发器，将逻辑放在应用层。
> 语法： create trigger <触发器名称> { before | after} # 之前或者之后出发 insert | update | delete # 指明了激活触发程序的语句的类型 on <表名> # 操作哪张表 for each row # 触发器的执行间隔，for each row 通知触发器每隔一行执行一次动作，而不是对整个表执行一次。 <触发器SQL语句>

```sql
delimiter $
CREATE TRIGGER set_userdate BEFORE INSERT 
on `message`
for EACH ROW
BEGIN
  set @statu = new.status; -- 声明复制变量 statu
  if @statu = 0 then       -- 判断 statu 是否等于 0
    UPDATE `user_accounts` SET status=1 WHERE openid=NEW.openid;
  end if;
END
$
DELIMITER ; -- 恢复结束符号
```
* NEW 用NEW.col_name，没有旧行。在DELETE触发程序中，仅能使用OLD.col_name，没有新行。
* OLD 用OLD.col_name来引用更新前的某一行的列

## 索引

普通索引
```sql
-- –直接创建索引
CREATE INDEX index_user ON user(title)
-- –修改表结构的方式添加索引
ALTER TABLE table_name ADD INDEX index_name ON (column(length))
-- 给 user 表中的 name 字段 添加普通索引(INDEX)
ALTER TABLE `user` ADD INDEX index_name (name)
-- –创建表的时候同时创建索引
CREATE TABLE `user` (
    `id` int(11) NOT NULL AUTO_INCREMENT ,
    `title` char(255) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL ,
    `content` text CHARACTER SET utf8 COLLATE utf8_general_ci NULL ,
    `time` int(10) NULL DEFAULT NULL ,
    PRIMARY KEY (`id`),
    INDEX index_name (title(length))
)
-- –删除索引
DROP INDEX index_name ON table
```

唯一索引
```sql
-- 给 user 表中的 creattime 字段添加唯一索引(UNIQUE)
ALTER TABLE `user` ADD UNIQUE (creattime);
```

全文索引
```sql
ALTER TABLE `user` ADD FULLTEXT (description);
```

多列索引
```sql
-- 给 user 表中的 name、city、age 字段添加名字为name_city_age的普通索引(INDEX)
ALTER TABLE user ADD INDEX name_city_age (name(10),city,age); 
```

### 建立索引的时机

在WHERE和JOIN中出现的列需要建立索引，但也不完全如此：
* MySQL只对<，<=，=，>，>=，BETWEEN，IN使用索引
* 某些时候的LIKE也会使用索引。
* 在LIKE以通配符%和_开头作查询时，MySQL不会使用索引。
```sql
-- 此时就需要对city和age建立索引，
-- 由于mytable表的userame也出现在了JOIN子句中，也有对它建立索引的必要。
SELECT t.Name  
FROM mytable t LEFT JOIN mytable m ON t.Name=m.username 
WHERE m.age=20 AND m.city='上海';

SELECT * FROM mytable WHERE username like'admin%'; -- 而下句就不会使用：
SELECT * FROM mytable WHERE Name like'%admin'; -- 因此，在使用LIKE时应注意以上的区别。
```

* 索引不会包含有NULL值的列
* 使用短索引
* 不要在列上进行运算 索引会失效

## 修改表
添加列
```sql
-- 在表students的最后追加列 address: 
alter table students add address char(60);
-- 在名为 age 的列后插入列 birthday: 
alter table students add birthday date after age;
-- 在名为 number_people 的列后插入列 weeks: 
alter table students add column `weeks` varchar(5) not null default "" after `number_people`;
```

修改列
```sql
-- 将表 tel 列改名为 telphone: 
alter table students change tel telphone char(13) default "-";
-- 将 name 列的数据类型改为 char(16): 
alter table students change name name char(16) not null;
-- 修改 COMMENT 前面必须得有类型属性
alter table students change name name char(16) COMMENT '这里是名字';
-- 修改列属性的时候 建议使用modify,不需要重建表
-- change用于修改列名字，这个需要重建表
alter table meeting modify `weeks` varchar(20) NOT NULL DEFAULT '' COMMENT '开放日期 周一到周日：0~6，间隔用英文逗号隔开';
-- `user`表的`id`列，修改成字符串类型长度50，不能为空，`FIRST`放在第一列的位置
alter table `user` modify COLUMN `id` varchar(50) NOT NULL FIRST ;
```

删除列
```sql
-- 删除表students中的 birthday 列: 
alter table students drop birthday;
```

重命名表
```sql
-- 重命名 students 表为 workmates: 
alter table students rename workmates;
```

清空表数据
```sql
-- 清空表为 workmates 里面的数据，不删除表。 
delete from workmates;
-- 删除workmates表中的所有数据，且无法恢复
truncate table workmates;
```

删除整张表
```sql
drop table workmates;
```

删除整个数据库
```sql
drop database samp_db;
```

SQL删除重复记录
```sql
-- 查找表中多余的重复记录，重复记录是根据单个字段（peopleId）来判断
select * from people where peopleId in (select peopleId from people group by peopleId having count(peopleId) > 1)
-- 删除表中多余的重复记录，重复记录是根据单个字段（peopleId）来判断，只留有rowid最小的记录
delete from people 
where peopleId in (select peopleId from people group by peopleId having count(peopleId) > 1)
and rowid not in (select min(rowid) from people group by peopleId having count(peopleId )>1)
-- 查找表中多余的重复记录（多个字段）
select * from vitae a
where (a.peopleId,a.seq) in (select peopleId,seq from vitae group by peopleId,seq having count(*) > 1)
-- 删除表中多余的重复记录（多个字段），只留有rowid最小的记录
delete from vitae a
where (a.peopleId,a.seq) in (select peopleId,seq from vitae group by peopleId,seq having count(*) > 1) and rowid not in (select min(rowid) from vitae group by peopleId,seq having count(*)>1)
-- 查找表中多余的重复记录（多个字段），不包含rowid最小的记录
select * from vitae a
where (a.peopleId,a.seq) in (select peopleId,seq from vitae group by peopleId,seq having count(*) > 1) and rowid not in (select min(rowid) from vitae group by peopleId,seq having count(*)>1)
```