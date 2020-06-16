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
E elementData(int index) {
    return (E) elementData[index];
}
/** 删除指定元素，若元素重复，则只删除下标最小的元素 */
public boolean remove(Object o) {
    if (o == null) {
        for (int index = 0; index < size; index++)
            if (elementData[index] == null) {
                fastRemove(index);
                return true;
            }
    } else {
        // 遍历数组，查找要删除元素的位置
        for (int index = 0; index < size; index++)
            if (o.equals(elementData[index])) {
                fastRemove(index);
                return true;
            }
    }
    return false;
}
/** 快速删除，不做边界检查，也不返回删除的元素值 */
private void fastRemove(int index) {
    modCount++;
    int numMoved = size - index - 1;
    if (numMoved > 0)
        System.arraycopy(elementData, index+1, elementData, index, numMoved);
    elementData[--size] = null; // clear to let GC do its work
}
```
删除操作除了删除元素，还要将 size 减一

![](img/15171118248304.jpg)

如果数组扩容之后删除多个元素导致大量空闲空间，程序员可以主动触发空间缩减的方法。

```java
/** 将数组容量缩小至元素数量 */
public void trimToSize() {
    modCount++;
    if (size < elementData.length) {
        elementData = (size == 0)
          ? EMPTY_ELEMENTDATA
          : Arrays.copyOf(elementData, size);
    }
}
```

### 2.4 遍历
ArrayList 实现了 RandomAccess 接口(表示它可以随机访问)，所以下标直接访问效率比较高。

```java
for (int i = 0; i < list.size(); i++) {
    list.get(i);
}
```

## 3 其他
### 3.1 快速失败

在 Java 集合框架中，很多类都实现了快速失败机制。该机制被触发时，会抛出并发修改异常ConcurrentModificationException 。

ArrayList 迭代器中的方法都是均具有快速失败的特性，当遇到并发修改的情况时，迭代器会快速失败，以避免程序在将来不确定的时间里出现不确定的行为。

### 3.2 遍历时删除

> 不要在 foreach 循环里进行元素的 remove/add 操作。remove 元素请使用 Iterator 方式，如果并发操作，需要对 Iterator 对象加锁。

```java
List<String> a = new ArrayList<String>();
	a.add("1");
	a.add("2");
	for (String temp : a) {
	    System.out.println(temp);
	    if("1".equals(temp)){
	        a.remove(temp);
	    }
	}
}
```

上面的程序执行起来不会虽不会出现异常，但代码执行逻辑上却有问题，只不过这个问题隐藏的比较深。我们把 temp 变量打印出来，会发现只打印了数字1，2没打印出来。初看这个执行结果确实很让人诧异，不明原因。如果死抠上面的代码，我们很难找出原因，此时需要稍微转换一下思路。我们都知道 Java 中的 foreach 是个语法糖，编译成字节码后会被转成用迭代器遍历的方式。所以我们可以把上面的代码转换一下，等价于下面形式：

```java
List<String> a = new ArrayList<>();
a.add("1");
a.add("2");
Iterator<String> it = a.iterator();
while (it.hasNext()) {
    String temp = it.next();
    System.out.println("temp: " + temp);
    if("1".equals(temp)){
        a.remove(temp);
    }
}
```

再去分析一下 ArrayList 的迭代器源码

```java
private class Itr implements Iterator<E> {
    int cursor;       // index of next element to return
    int lastRet = -1; // index of last element returned; -1 if no such
    int expectedModCount = modCount;
    public boolean hasNext() {
        return cursor != size;
    }
    @SuppressWarnings("unchecked")
    public E next() {
        // 并发修改检测，检测不通过则抛出异常
        checkForComodification();
        int i = cursor;
        if (i >= size)
            throw new NoSuchElementException();
        Object[] elementData = ArrayList.this.elementData;
        if (i >= elementData.length)
            throw new ConcurrentModificationException();
        cursor = i + 1;
        return (E) elementData[lastRet = i];
    }
    final void checkForComodification() {
        if (modCount != expectedModCount)
            throw new ConcurrentModificationException();
    }
    // 省略不相关的代码
}
```

删除元素 1 后，元素计数器 size = 1，而迭代器中的 cursor 也等于 1，从而导致 it.hasNext() 返回false。归根结底，上面的代码段没抛异常的原因是，循环提前结束，导致 next 方法没有机会抛异常。

## 4 总结

## 来源文章

http://www.tianxiaobo.com/2018/01/28/ArrayList%E6%BA%90%E7%A0%81%E5%88%86%E6%9E%90/