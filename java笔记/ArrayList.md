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
// 带初始容量参数的构造函数。（用户自己指定容量）
public ArrayList(int initialCapacity) {
    if (initialCapacity > 0) {
        // 初始容量大于0,创建initialCapacity大小的数组
        this.elementData = new Object[initialCapacity];
    } else if (initialCapacity == 0) {
        // 创建空数组
        this.elementData = EMPTY_ELEMENTDATA;
    } else {
        throw new IllegalArgumentException("Illegal Capacity: "+ initialCapacity);
    }
}
// 默认构造函数，使用初始容量10构造一个空列表(无参数构造)
public ArrayList() {
    this.elementData = DEFAULTCAPACITY_EMPTY_ELEMENTDATA;
}
/**
* 构造包含指定collection元素的列表，这些元素利用该集合的迭代器按顺序返回
* 如果指定的集合为null，throws NullPointerException。 
*/
public ArrayList(Collection<? extends E> c) {
    elementData = c.toArray();
    if ((size = elementData.length) != 0) {
        // c.toArray might (incorrectly) not return Object[] (see 6260652)
        if (elementData.getClass() != Object[].class)
            elementData = Arrays.copyOf(elementData, size, Object[].class);
    } else {
        // replace with empty array.
        this.elementData = EMPTY_ELEMENTDATA;
    }
}
> 以无参数构造方法创建 ArrayList 时，实际上初始化赋值的是一个空数组。当真正对数组进行添加元素操作时，才真正分配容量。即向数组中添加第一个元素时，数组容量扩为10。
```

### 2.2 插入

```java
/** 在元素序列尾部插入 */
public boolean add(E e) {
    // 1. 检测是否需要扩容
    ensureCapacityInternal(size + 1);  // Increments modCount!!
    // 2. 将新元素插入序列尾部
    elementData[size++] = e;
    return true;
}
/** 在元素序列 index 位置处插入 */
public void add(int index, E element) {
    rangeCheckForAdd(index);
    // 1. 检测是否需要扩容
    ensureCapacityInternal(size + 1);  // Increments modCount!!
    // 2. 将 index 及其之后的所有元素都向后移一位
    System.arraycopy(elementData, index, elementData, index + 1, size - index);
    // 3. 将新元素插入至 index 处
    elementData[index] = element;
    size++;
}
```

在队尾插入

![](img/15171117436209.jpg)

在元素序列指定位置（假设该位置合理）插入

![](img/15171117759805.jpg)

> 将新元素插入至序列指定位置，时间复杂度为O(N)。

扩容

```java
/** 计算最小容量 */
private static int calculateCapacity(Object[] elementData, int minCapacity) {
    if (elementData == DEFAULTCAPACITY_EMPTY_ELEMENTDATA) {
        return Math.max(DEFAULT_CAPACITY, minCapacity);
    }
    return minCapacity;
}
/** 扩容的入口方法 */
private void ensureCapacityInternal(int minCapacity) {
    ensureExplicitCapacity(calculateCapacity(elementData, minCapacity));
}
private void ensureExplicitCapacity(int minCapacity) {
    modCount++;
    // overflow-conscious code
    if (minCapacity - elementData.length > 0)
        grow(minCapacity);
}
/** 扩容的核心方法 */
private void grow(int minCapacity) {
    // overflow-conscious code
    int oldCapacity = elementData.length;
    // newCapacity = oldCapacity + oldCapacity / 2 = oldCapacity * 1.5
    int newCapacity = oldCapacity + (oldCapacity >> 1);
    if (newCapacity - minCapacity < 0)
        newCapacity = minCapacity;
    if (newCapacity - MAX_ARRAY_SIZE > 0)
        newCapacity = hugeCapacity(minCapacity);
    // 扩容
    elementData = Arrays.copyOf(elementData, newCapacity);
}
private static int hugeCapacity(int minCapacity) {
    if (minCapacity < 0) // overflow
        throw new OutOfMemoryError();
    // 如果最小容量超过 MAX_ARRAY_SIZE，则将数组容量扩容至 Integer.MAX_VALUE
    return (minCapacity > MAX_ARRAY_SIZE) ?
        Integer.MAX_VALUE :
        MAX_ARRAY_SIZE;
}
```

扩容的入口方法

-> 给定一个最小容量值(假如这个值不超过上下限) 

-> 如果给定的值大于集合的长度，则扩容 

-> 新的容量=旧的容量*1.5 

-> 如果 newCapacity 依旧小于 minCapacity ，则直接 ，newCapacity = minCapacity 

-> newCapacity 大于 MAX_ARRAY_SIZE(数组容量上限)，newCapacity = MAX_ARRAY_SIZE和minCapacity的最大值

### 2.3 删除

```java
/** 删除指定位置的元素 */
public E remove(int index) {
    rangeCheck(index);
    modCount++;
    // 返回被删除的元素值
    E oldValue = elementData(index);
    int numMoved = size - index - 1;
    if (numMoved > 0)
        // 将 index + 1 及之后的元素向前移动一位，覆盖被删除值
        System.arraycopy(elementData, index+1, elementData, index, numMoved);
    // 将最后一个元素置空，并将 size 值减1                
    elementData[--size] = null; // clear to let GC do its work
    return oldValue;
}
```
```java

```
### 2.4 遍历
## 3 其他
### 3.1 快速失败
### 3.2 遍历时删除
## 4 总结

## 来源文章

http://www.tianxiaobo.com/2018/01/28/ArrayList%E6%BA%90%E7%A0%81%E5%88%86%E6%9E%90/