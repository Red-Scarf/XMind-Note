# URL
组成：
* router 路由，即域名网址
* search 查询字符串，即`?`后的字符串，以键值对的形式通过`&`连接
* hash 哈希值/锚，`#`后面的字符串，一般作为单页应用的路由地址，或者文档的锚

# Headers
## Content-Type
* `application/x-www-form-urlencoded` 最常见的方式以键值对的字符串传输，但不能传输文件，几乎所有的 ajax 框架都是默认以此种方式发送。
* `multipart/form-data` 以键值对的形式通过分隔符链接，以字符串给后台，可以传输文件，也可以传输普通数据
* `text/plain` 很少会用到，普通文本，可以是任意数据，除了文件
* `binary` 二进制流，仅限一个文件

## Data-Type
告诉后台你希望返回什么类型的数据,如xml，html，script，json，jsonp，text等。

## 自定义header
如果跟后台有约定header，如token等,也可传到后台。

