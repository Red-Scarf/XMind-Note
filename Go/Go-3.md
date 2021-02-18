# 指针
变量是一种使用方便的占位符，用于引用计算机内存地址。Go 语言的取地址符是 &，放到一个变量前使用就会返回相应变量的内存地址。

一个指针变量指向了一个值的内存地址。类似于变量和常量，在使用指针前你需要声明指针。
```go
var var_name *var-type
```
> var-type 为指针类型，var_name 为指针变量名，* 号用于指定变量是作为一个指针。
使用流程：
* 定义指针变量。
* 为指针变量赋值。
* 访问指针变量中指向地址的值。
> 在指针类型前面加上 * 号（前缀）来获取指针所指向的内容。

```go
var a int = 10
var pi *int = &a
fmt.Println("value a:", a) // 10
fmt.Println("address a:", &a) // a 的地址
fmt.Println("value pi:", pi) // a 的地址
fmt.Println("value *pi:", *pi) // 10
fmt.Println("address pi:", &pi) // pi 的地址
```
## 空指针
当一个指针被定义后没有分配到任何变量时，它的值为 `nil`。

nil在概念上和其它语言的null、None、nil、NULL一样，都指代零值或空值。

一个指针变量通常缩写为 ptr。
```go
var pi *int
fmt.Println("value pi:", pi) // nil
fmt.Println("value *pi:", *pi) // 运行时报错
fmt.Println("address pi:", &pi) // 地址
```

## 指针数组
```go
var ptr [MAX]*int
```

## 指向指针的指针
```go
var a int
var ptr *int
var pptr **int
a = 3000
ptr = &a
pptr = &ptr
fmt.Printf("变量 a = %d\n", a) // 3000
fmt.Printf("指针变量 *ptr = %d\n", *ptr) // 3000
fmt.Printf("指向指针的指针变量 **pptr = %d\n", **pptr) // 3000
```
也可以实现三重指针
```go
var ppptr ***int
```

## 函数指针变量

# 结构体
# 切片
# 范围
# Map
# 递归
# 类型转换
# 接口
# 错误处理
# 并发