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

## 函数指针参数
```go
swap(&a, &b);
func swap(x *int, y *int) {
   var temp int
   temp = *x    /* 保存 x 地址的值 */
   *x = *y      /* 将 y 赋值给 x */
   *y = temp    /* 将 temp 赋值给 y */
}
```

# 结构体
结构体是由一系列具有相同类型或不同类型的数据构成的数据集合。
* 定义
```go
type struct_variable_type struct {
    member definition
    member definition
    ...
    member definition
}
```
* 声明
```go
variable_name := structure_variable_type {value1, value2...valuen}
或
variable_name := structure_variable_type { key1: value1, key2: value2..., keyn: valuen}
// 忽略的字段为0或者空
```
* 使用结构体用`.`即可
```go
结构体.成员名
```
* 结构体可以作为参数传递
* 结构体声明时，首字母大写则其他包可以调用，否则只能本包内调用。首字母大写时，编译会提示需要注释。

## 结构体指针
使用指针时，如果需要使用指针访问结构体成员，不能使用`*`，直接`指针变量.成员名`

# Map
# 递归
# 类型转换
# 接口
# 错误处理
# 并发