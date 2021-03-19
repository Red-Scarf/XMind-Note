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