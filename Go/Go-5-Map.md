# 概念
Map 是一种无序的键值对的集合。Map 最重要的一点是通过 key 来快速检索数据，key 类似于索引，指向数据的值。

Map 是一种集合，可以像迭代数组和切片那样迭代。因为 Map 是使用 hash 表来实现，所以 Map 无序。

# 定义 声明
可以函数 make 也可以使用 map 关键字来定义 Map
```go
map_variable := make(map[key_data_type]value_data_type)
var map_variable map[key_data_type]value_data_type
```
> 如果不初始化 map，那么就会创建一个 nil map。nil map 不能用来存放键值对




