# 运算符
## 算术运算符
`+` `-` `*` `/` `%` `++` `--` 
> go 支持 a++ 不支持 ++a

## 关系运算符
* `==` 检查两个值是否相等，如果相等返回 True 否则返回 False。 (A == B) 为 False
* `!=` 检查两个值是否不相等，如果不相等返回 True 否则返回 False。 (A != B) 为 True
* `>` 检查左边值是否大于右边值，如果是返回 True 否则返回 False。 (A > B) 为 False
* `<` 检查左边值是否小于右边值，如果是返回 True 否则返回 False。 (A < B) 为 True
* `>=` 检查左边值是否大于等于右边值，如果是返回 True 否则返回 False。 (A >= B) 为 False
* `<=` 检查左边值是否小于等于右边值，如果是返回 True 否则返回 False。 (A <= B) 为 True

## 逻辑运算符
* `&&` 逻辑 AND 运算符。 如果两边的操作数都是 True，则条件 True，否则为 False。 (A && B) 为 False
* `||` 逻辑 OR 运算符。 如果两边的操作数有一个 True，则条件 True，否则为 False。 (A || B) 为 True
* `!` 逻辑 NOT 运算符。 如果条件为 True，则逻辑 NOT 条件 False，否则为 True。 !(A && B) 为 True

## 位运算符
假定 A 为60，B 为13
* `&` 按位与运算符"&"是双目运算符。 其功能是参与运算的两数各对应的二进位相与。 (A & B) 结果为 12, 二进制为 0000 1100
* `|` 按位或运算符"|"是双目运算符。 其功能是参与运算的两数各对应的二进位相或 (A | B) 结果为 61, 二进制为 0011 1101
* `^` 按位异或运算符"^"是双目运算符。 其功能是参与运算的两数各对应的二进位相异或，当两对应的二进位相异时，结果为1。 (A ^ B) 结果为 49, 二进制为 0011 0001
* `<<` 左移运算符"<<"是双目运算符。左移n位就是乘以2的n次方。 其功能把"<<"左边的运算数的各二进位全部左移若干位，由"<<"右边的数指定移动的位数，高位丢弃，低位补0。 A << 2 结果为 240 ，二进制为 1111 0000
* `>>` 右移运算符">>"是双目运算符。右移n位就是除以2的n次方。 其功能是把">>"左边的运算数的各二进位全部右移若干位，">>"右边的数指定移动的位数。 A >> 2 结果为 15 ，二进制为 0000 1111

`&`, `|`, `^`
| `p` | `q` | `p&q` | `p|q` | `p^q` |
|-|-|-|-|-|-|
| 0 | 0 | 0 | 0 | 0 |
| 0 | 1 | 0 | 1 | 1 |
| 1 | 1 | 1 | 1 | 0 |
| 1 | 0 | 0 | 1 | 1 |

```
A = 60; B = 13
-----------------
A = 0011 1100
B = 0000 1101
-----------------
A&B = 0000 1100
A|B = 0011 1101
A^B = 0011 0001
```

## 赋值运算符
* `=` 简单的赋值运算符，将一个表达式的值赋给一个左值 `C = A + B` 将 A + B 表达式结果赋值给 C
* `+=` 相加后再赋值 `C += A` 等于 `C = C + A`
* `-=` 相减后再赋值 `C -= A` 等于 `C = C - A`
* `*=` 相乘后再赋值 `C *= A` 等于 `C = C * A`
* `/=` 相除后再赋值 `C /= A` 等于 `C = C / A`
* `%=` 求余后再赋值 `C %= A` 等于 `C = C % A`
* `<<=` 左移后赋值 `C <<= 2` 等于 `C = C << 2`
* `>>=` 右移后赋值 `C >>= 2` 等于 `C = C >> 2`
* `&=` 按位与后赋值 `C &= 2` 等于 `C = C & 2`
* `^=` 按位异或后赋值 `C ^= 2` 等于 `C = C ^ 2`
* `|=` 按位或后赋值 `C |= 2` 等于 `C = C | 2`

## 其他运算符
* `&` 返回变量存储地址 `&a;` 将给出变量的实际地址。
* `*` 指针变量。 `*a;` 是一个指针变量

## 运算符优先级
* 5 级，`* / % << >> & &^`
* 4 级，`+ - | ^`
* 3 级，`== != < <= > >=`
* 2 级，`&&`
* 1 级，`||`

# 条件语句
## if else
和其他语言一样的 `if else`
```go
if a < 20 {
    fmt.Printf("a 小于 20\n" );
    if b == 200 {
        fmt.Printf("b 的值为 200\n" );
    }
} else {
    fmt.Printf("a 不小于 20\n" );
}
```

## switch
switch 基本用法：
* switch 语句用于基于不同条件执行不同动作，每一个 case 分支都是唯一的，从上至下逐一测试，直到匹配为止。
* switch 语句执行的过程从上至下，直到找到匹配项，匹配项后面也不需要再加 break。switch 默认情况下 case 最后自带 break 语句，匹配成功后就不会执行其他 case，如果我们需要执行后面的 case，可以使用 `fallthrough` 。
* 支持多条件匹配
```go
// 变量 var1 可以是任何类型，而 val1 和 val2 则可以是同类型的任意值。类型不被局限于常量或整数，但必须是相同的类型；或者最终结果为相同类型的表达式。
switch var1 {
    case val1:
        ...
    case val2:
        ...
    default:
        ...
}
```
```go
// 多条件匹配
switch{
    case 1,2,3,4:
    default:
}
```
```go
switch{
    case 1:
    ...
    if(...){
        break
    }
    fallthrough // 此时switch(1)会执行case1和case2，但是如果满足if条件，则只执行case1
    case 2:
    ...
    case 3:
}
```

* switch 语句还可以被用于 type-switch 来判断某个 interface 变量中实际存储的变量类型。

```go
var x interface{}
switch i := x.(type) {
    case nil:  
        fmt.Printf(" x 的类型 :%T",i)                
    case int:  
        fmt.Printf("x 是 int 型")                      
    case float64:
        fmt.Printf("x 是 float64 型")          
    case func(int) float64:
        fmt.Printf("x 是 func(int) 型")                      
    case bool, string:
        fmt.Printf("x 是 bool 或 string 型" )      
    default:
        fmt.Printf("未知型")    
}
// x 的类型 :<nil>
```

* 使用 fallthrough 会强制执行后面的 case 语句，fallthrough 不会判断下一条 case 的表达式结果是否为 true。
```go
switch {
    case false:
        fmt.Println("1、case 条件语句为 false")
        fallthrough
    case true:
        fmt.Println("2、case 条件语句为 true")
        fallthrough
    case false:
        fmt.Println("3、case 条件语句为 false")
        fallthrough
    case true:
        fmt.Println("4、case 条件语句为 true")
    case false:
        fmt.Println("5、case 条件语句为 false")
        fallthrough
    default:
        fmt.Println("6、默认 case")
}
// 2、case 条件语句为 true
// 3、case 条件语句为 false
// 4、case 条件语句为 true
```
> switch 从第一个判断表达式为 true 的 case 开始执行，如果 case 带有 fallthrough，程序会继续执行下一条 case，且它不会去判断下一个 case 的表达式是否为 true。

## select
select 是 Go 比较特殊的一种控制语句，类似于用于通信的 switch 语句。每个 case 必须是一个通信操作，要么是发送要么是接收。select 随机执行一个可运行的 case。如果没有 case 可运行，它将阻塞，直到有 case 可运行。一个默认的子句应该总是可运行的。
```go
select {
    case communication clause  :
       statement(s);      
    case communication clause  :
       statement(s);
    /* 你可以定义任意数量的 case */
    default : /* 可选 */
       statement(s);
}
```

# 循环语句
## for 循环
Go 语言的 For 循环有 3 种形式，只有其中的一种使用分号
```go
// C 语言的 for 一样
for init; condition; post { }
// C 的 while 一样
for condition { }
// C 的 for(;;) 一样
for { }
```
* init：一般为赋值表达式，给控制变量赋初值；
* condition：关系表达式或逻辑表达式，循环控制条件；
* post：一般为赋值表达式，给控制变量增量或减量。

for 执行过程：
先对表达式 1 赋初值；判别赋值表达式 init 是否满足给定条件，若其值为真，满足循环条件，则执行循环体内语句，然后执行 post，进入第二次循环，再判别 condition；否则判断 condition 的值为假，不满足条件，就终止for循环，执行循环体外语句。

## For-each range 循环
可以对字符串、数组、切片等进行迭代输出元素。
```go
strings := []string{"google", "runoob"}
for i, s := range strings {
    fmt.Println(i, s)
}
numbers := [6]int{1, 2, 3, 5}
for i,x:= range numbers {
    fmt.Printf("第 %d 位 x 的值 = %d\n", i,x)
}  
```

## break 和 continue
使用 break 和 continue 时需要用分号，可以使用 break 跳出多重循环
```go
for a < 20 {
    a++;
    if a > 15 {
        break;
    }
    if a < 5 {
        continue;
    }
    fmt.Printf("a 的值为 : %d\n", a);
}
```
break 和 continue 也可以跳转到指定标签的地方
```go
for1: for i := 0; i < 10; i++ {
    fmt.Println("i:", i)
    for2: for j := 0; j < 10; j++ {
        fmt.Println("j:", j)
        if i == 8 && j == 7 {
            break for1
        }
        if i == 6 && j == 9 {
            break for2
        }
        if i == 5 && j == 8 {
            continue for1
        }
    }
}
```
> go 也有 goto 可用，但依旧不建议使用 goto

# 函数
Go 语言最少有个 main() 函数。Go 语言标准库提供了多种可动用的内置的函数。
## 基础函数定义
```go
func function_name( [parameter list] ) [return_types] {
   函数体
}
```
1. `func`：函数由 func 开始声明
2. `function_name`：函数名称，函数名和参数列表一起构成了**函数签名**。
3. `parameter list`：参数列表，参数就像一个占位符，当函数被调用时，你可以将值传递给参数，这个值被称为实际参数。参数列表指定的是参数类型、顺序、及参数个数。参数是可选的，也就是说函数也可以不包含参数。
4. `return_types`：返回类型，函数返回一列值。return_types 是该列值的数据类型。有些功能不需要返回值，这种情况下 return_types 不是必须的。
5. `函数体`：函数定义的代码集合。

## 多个返回值
> Go 函数可以返回多个值
```go
func swap(x, y string) (string, string) {
   return y, x
}
func main() {
   a, b := swap("Google", "Runoob")
   fmt.Println(a, b)
}
```

## 参数传递
Go 语言默认使用值传递

Go 可以使用指针实现引用传递，引用传递指针参数传递到函数内
> `*int` 只能接受 int 类型的取地址符获取到的地址

## 函数作为参数
和 JavaScript 类似，Go 的函数可以作为变量使用
```go
type fc func(int) int
func main() {
	testCallback(2, callback)
	testCallback(5, func(i int) int {
		fmt.Println("anonymous callback")
		return i + i
	})
}
func testCallback(a int, f fc) {
	fmt.Println("call the callback func")
	aaa := f(a)
	fmt.Println(aaa)
}
func callback(a int) int {
	fmt.Println("callback")
	return a * a
}
```

## 闭包
Go 语言支持匿名函数，可作为闭包。匿名函数是一个"内联"语句或表达式。匿名函数的优越性在于可以直接使用函数内的变量，不必声明。

> 在回调函数a中，返回一个函数b ，函数b 中使用了回调函数a 内部的变量，从而使外部的方法能获取并使用回调函数a 内部的变量。

```go
func main() {
	add_func := add(1, 2)
	fmt.Println(add_func()) // 1 3
	fmt.Println(add_func()) // 2 3
	fmt.Println(add_func()) // 3 3
}
func add(a, b int) func() (int, int) {
	i := 0
	return func() (int, int) {
		i++
		return i, a + b
	}
}
```

## 方法
Go 语言中同时有函数和方法。一个方法就是一个包含了接受者的函数，接受者可以是命名类型或者结构体类型的一个值或者是一个指针。所有给定类型的方法属于该类型的方法集。
> 给类型赋一个方法

```go
type yuan struct {
	r float64
}
func main() {
	var y yuan
	y.r = 10
	fmt.Println(y.mianji())
}
// 指定 yuan 就是本方法的类
func (y yuan) mianji() float64 {
	return 3.14 * y.r * y.r
}
```

# 作用域
* 函数内定义的变量称为局部变量，作用域只在函数体内，参数和返回值变量也是局部变量。
* 函数外定义的变量称为全局变量，全局变量可以在整个包甚至外部包（被导出后）使用。
* 函数定义中的变量称为形式参数

# 数组
声明数组
```go
var variable_name [SIZE] variable_type
```
初始化数组
```go
var balance = [5]float32{1000.0, 2.0, 3.4, 7.0, 50.0}
```
如果数组长度不确定，可以使用 `...` 代替数组的长度，编译器会根据元素个数自行推断数组的长度

如果设置了数组的长度，我们还可以通过指定下标来初始化元素
```go
balance := [5]float32{1:2.0, 3:7.0} // 初始化下标1和3
```

声明多维数组
```go
var variable_name [SIZE1][SIZE2]...[SIZEN] variable_type
```
初始化二维数组
```go
a := [3][4]int{  
    {0, 1, 2, 3},
    {4, 5, 6, 7},
    {8, 9, 10, 11},
}
```
> 注意：以上代码中倒数第二行的 } 必须要有逗号，因为最后一行的 } 不能单独一行，也可以写成这样
```go
a := [3][4]int{  
    {0, 1, 2, 3},
    {4, 5, 6, 7},
    {8, 9, 10, 11}}
```

> 函数的形参如果是数组，则实参的数组类型必须一致，连声明数组时的数组个数也要一致。如果声明数组时没有指定数组长度，就表示是切片，则形参也不能指定数组长度(为切片)。