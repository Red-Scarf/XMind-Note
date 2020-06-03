## HashMap
![](img/26341ef9fe5caf66ba0b7c40bba264a5_r.jpg)

### 介绍
接口java.util.Map，此接口主要有四个常用的实现类，分别是HashMap、Hashtable、LinkedHashMap和TreeMap。
* HashMap: 根据键的 hashCode 值存数据,大多数情况可以直接定位值,访问速度快。支持 null 作为键。如果需要满足线程安全，可以用 Collections的synchronizedMap方法使HashMap具有线程安全的能力。
* Hashtable: 遗留类,不建议用。
* LinkedHashMap: 底层使用 HashMap,保存了值插入的顺序。
* TreeMap: 默认根据键升序排序。使用时,key 必须实现 Comparable 接口或者构造 TreeMap 时传入自定义的 Comparator 。
> 以上均要求 key 不可变,或者说 key 的 hashCode 不可变。

HashMap 原理,好好通读该文章 https://zhuanlan.zhihu.com/p/21673805 