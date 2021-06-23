# 安装 MongoDB
下载MongoDB压缩包
```
wget https://fastdl.mongodb.org/linux/mongodb-linux-x86_64-rhel70-4.4.4.tgz
```
解压压缩包
```
tar -zvxf mongodb-linux-x86_64-rhel70-4.4.4.tgz
```
重命名文件夹
```
mongodb-package]# mv mongodb-linux-x86_64-rhel70-4.4.4 mongodb
```
将mongodb放入 /usr/local 
配置环境变量
```
vim /etc/profile
```
在 export PATH USER LOGNAME MAIL HOSTNAME HISTSIZE HISTCONTROL 一行的上面添加
```
#Set Mongodb
export PATH=/usr/mongodb/bin:$PATH
```
使环境变量生效
```
source /etc/profile
```
创建数据库目录
```
cd /usr/local/mongodb
touch mongodb.conf
mkdir db
mkdir log
cd log
touch mongodb.log
```
修改mongodb配置文件
```
port=27017 #端口
dbpath= /usr/mongodb/db #数据库存文件存放目录
logpath= /usr/mongodb/log/mongodb.log #日志文件存放路径
logappend=true #使用追加的方式写日志
fork=true #以守护进程的方式运行，创建服务器进程
maxConns=100 #最大同时连接数
noauth=true #不启用验证
journal=true #每次写入会记录一条操作日志（通过journal可以重新构造出写入的数据）。
#即使宕机，启动时wiredtiger会先将数据恢复到最近一次的checkpoint点，然后重放后续的journal日志来恢复。
storageEngine=wiredTiger  #存储引擎有mmapv1、wiretiger、mongorocks
bind_ip = 0.0.0.0  #这样就可外部访问了，例如从win10中去连虚拟机中的MongoDB
```
将db和log文件夹权限都设置为777
启动MongoDB
```
mongod --config /usr/mongodb/mongodb.conf
```
查看数据库启动情况
```
ps ax | grep mongod
```
进入MongoDB控制台
```
mongo
```
关闭mongod：查到 pid 之后，执行kill命令
```
kill <pid>
```
MongoDB默认不启用用户认证，先创建用户后，修改配置文件
```
# 新增
auth=true
```
# MongoDB命令
show dbs - 查看所有数据库
use <db name> - 进入数据库
db.createUser() - 创建用户
```
db.createUser(
  {
    user: "root",
    pwd: "ROOTroot123",
    roles: [
        {role:"dbAdmin", db:"demo"}
    ]
  }
)
db.createUser(
  {
    user: "redscard",
    pwd: "邓志远23456",
    roles: [ "root" ]
  }
)
```
创建两个用户

# 用户权限
* 数据库用户角色：read、readWrite;
  * 允许用户 读/读写 指定的数据库
* 数据库管理角色：dbAdmin、dbOwner；
  * 允许在指定数据库中执行管理函数 , 如索引增删 查看统计等
* 用户管理角色：userAdmin
  * 允许向system.users集合写入数据, 可以在指定数据库增删用户
* 集群管理角色：clusterAdmin、clusterManager、clusterMonitor、hostManager；
* 备份恢复角色：backup、restore；
* 所有数据库角色：readAnyDatabase、readWriteAnyDatabase、userAdminAnyDatabase、dbAdminAnyDatabase
  * 可以在所有数据库中进行 读/读写/用户管理
* 超级用户角色：root
* 内部角色：__system

## 修改用户权限

```
db.updateUser("usertest",{roles:[ {role:"read",db:"testDB"} ]})
```
updateuser它是完全替换之前的值。