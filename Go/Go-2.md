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
* 和其他语言一样的 `if else`
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