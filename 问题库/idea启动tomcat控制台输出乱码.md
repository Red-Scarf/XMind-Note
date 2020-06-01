刚开始碰到的时候没调试成功又放下了，老系统放在 MyEclipse 下面跑，这两天 MyEclipse 和 Tomcat 老出问题，借着这个机会又试了试，成功了。

大部分调试方法来自[这里](./idea启动tomcat控制台输出乱码-2.md "idea启动tomcat控制台输出乱码-2")，但是有微调。

在 tomcat Server 中设置 VM options ， 值为 -Dfile。encoding=UTF-8，可惜没生效

注意: 刚开始我也是设成UTF-8，但设了一圈回来，Console 窗口里日志行的信息两个字是生效了，但日志行内容还是乱码，于是试着把这一步的设置改成 GBK，居然成功了。

2、在setting中的 File encodings 中设置编码格式，后来发现这是设置页面编码格式的，所以也没生效，不过遇到相关问题的朋友也不防照此设置下。
> 这些应该在刚装完 IDEA 就要设置了

3、在java Complier中设置Additional command line parameters的值，-encoding=UTF-8，很可惜还没生效

4、在bin中设置idea.exe.vmoptions和idea64.exe.vmoptions中的参数，同时增加-Dfile.encoding=UTF-8，据说有些人保存后重启就可以了，但到我这边还是没生效。

5、在tomcat \bin目录下的catalina.bat文件中加入 -Dfile.encoding=UTF-8，可是还不生效，有些抓狂了...

6、在 tomcat / conf 目录下，设置 logging.properties ，增加参数  java.util.logging.ConsoleHandler.encoding = GBK，重启后终于可以了，总算松了口气。

这些一起加在末尾
```
catalina.org.apache.juli.FileHandler.encoding = GBK
localhost.org.apache.juli.FileHandler.encoding = GBK
manager.org.apache.juli.FileHandler.encoding = GBK
host-manager.org.apache.juli.FileHandler.encoding = GBK
java.util.logging.ConsoleHandler.encoding = GBK
```
终于，正常显示了......