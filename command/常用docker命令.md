# 常用docker命令
## centos 下安装docker
* 卸载docker
```
$ sudo yum remove docker \
    docker-client \
    docker-client-latest \
    docker-common \
    docker-latest \
    docker-latest-logrotate \
    docker-logrotate \
    docker-selinux \
    docker-engine-selinux \
    docker-engine
```
* 安装依赖包
```
$ sudo yum install -y yum-utils \
    device-mapper-persistent-data \
    lvm2
```
* 执行下面的命令添加 yum 国内软件源
```
$ sudo yum-config-manager \
    --add-repo \
    https://mirrors.ustc.edu.cn/docker-ce/linux/centos/docker-ce.repo
```
### 安装 Docker CE
* 更新 yum 软件源缓存，并安装 docker-ce
```
$ sudo yum makecache fast
$ sudo yum install docker-ce
```
* 使用脚本自动安装
```
$ curl -fsSL get.docker.com -o get-docker.sh
$ sudo sh get-docker.sh --mirror Aliyun
```
* 启动 Docker CE
```
$ sudo systemctl enable docker
$ sudo systemctl start docker
```

### 建立 docker 用户组
默认情况下，docker 命令会使用 Unix socket 与 Docker 引擎通讯。而只有 root 用户和 docker 组的用户才可以访问 Docker 引擎的 Unix socket。出于安全考虑，一般 Linux 系统上不会直接使用 root 用户。因此，更好地做法是将需要使用 docker 的用户加入 docker 用户组。
* 建立 docker 组
```
$ sudo groupadd docker
```
* 将当前用户加入 docker 组
```
$ sudo usermod -aG docker $USER
```
* 列出本机的所有 image 文件。
```
$ docker image ls
```
* 删除 image 文件
```
$ docker image rm [imageName]
```
* 将 image 文件从仓库抓取到本地
```
$ docker image pull library/hello-world
```
* 运行 image 文件
> docker container run命令具有自动抓取 image 文件的功能。如果发现本地没有指定的 image 文件，就会从仓库自动抓取。
```
$ docker container run hello-world
```
* 有些容器不会自动终止，因为提供的是服务。比如，安装运行 Ubuntu 的 image，就可以在命令行体验 Ubuntu 系统。
```
$ docker container run -it ubuntu bash
```
* 对于那些不会自动终止的容器，必须使用docker container kill 命令手动终止。
```
$ docker container kill [containID]
```
* 查看完整的 containerId
```
docker ps --no-trunc
```
* 列出本机正在运行的容器
```
$ docker container ls
```
* 列出本机所有容器，包括终止运行的容器
```
$ docker container ls --all
```
* 终止运行的容器文件，依然会占据硬盘空间，可以使用docker container rm命令删除
```
$ docker container rm [containerID]
```
* 使用`--restart`命令设置容器自动重启
```
docker run -m 512m --memory-swap 1G -it -p 58080:8080 --restart=always 
--name bvrfis --volumes-from logdata mytomcat:4.0 /root/run.sh
```
* `--restart`详细参数
    * no -  容器退出时，不重启容器
    * on-failure - 只有在非0状态退出时才从新启动容器
    * always - 无论退出状态是如何，都重启容器
    * 还可以在使用on - failure策略时，指定Docker将尝试重新启动容器的最大次数
* 如果创建时未指定`--restart=always`，可通过update 命令
```
docker update --restart=always xxx
```