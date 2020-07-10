# virtual box挂载共享文件夹
## 安装增强功能包
在虚拟机装好后，启动虚拟机，然后点击“设备”->“安装增强功能”进行安装

## 执行代码
```
sudo mount -t vboxsf alpine /alpine
```
`alpine` 表示外部 virtual box 设置的文件夹挂载名。

`/alpine` 代表虚拟机内部自己的文件路径。

> 名字可以随意取，不需要一致。