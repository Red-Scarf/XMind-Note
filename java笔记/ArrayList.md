# ArrayList
## 1 概述
* ArrayList 是一种变长的集合类，基于定长数组实现。
* 允许空值和重复元素，且自动扩容。
* 可以在 O(1) 复杂度下完成随机查找操作。
* 非线程安全。
## 2 源码
### 2.1 构造
```java
private static final int DEFAULT_CAPACITY = 10; // 默认容量
private static final Object[] EMPTY_ELEMENTDATA = {}; // 默认空集合
private static final Object[] DEFAULTCAPACITY_EMPTY_ELEMENTDATA = {};
transient Object[] elementData;
public ArrayList(int initialCapacity) {
    if (initialCapacity > 0) {
        this.elementData = new Object[initialCapacity];
    } else if (initialCapacity == 0) {
        this.elementData = EMPTY_ELEMENTDATA;
    } else {
        throw new IllegalArgumentException("Illegal Capacity: "+ initialCapacity);
    }
}

public ArrayList() {
    this.elementData = DEFAULTCAPACITY_EMPTY_ELEMENTDATA;
}
```
```java

```
```java

```
```java

```
```java

```
### 2.2 插入
### 2.3 删除
### 2.4 遍历
## 3 其他
### 3.1 快速失败
### 3.2 遍历时删除
## 4 总结