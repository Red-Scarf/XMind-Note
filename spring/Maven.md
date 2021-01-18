# Maven 知识点
## dependencyManagement
主要作用是作为版本的管理，不会引入实际的 jar 包。

当继承的 pom 中声明 dependency 没有 version 元素时，Maven 会去 dependencyManagement 寻找相应的依赖版本。如果存在就会声明，如果不存在就报错提示需要 version。

只有当子 pom 的 dependency 声明依赖时，才会引入 jar 包。

## resources 属性配置
```
<build>
    <!-- 资源目录 -->    
    <resources>    
        <resource>    
            <!-- 设定主资源目录  -->    
            <directory>src/main/java</directory>    

            <!-- maven default生命周期，process-resources阶段执行maven-resources-plugin插件的resources目标处理主资源目下的资源文件时，只处理如下配置中包含的资源类型 -->     
                <includes>
                    <include>**/*.xml</include>
                </includes>  
                    
            <!-- maven default生命周期，process-resources阶段执行maven-resources-plugin插件的resources目标处理主资源目下的资源文件时，不处理如下配置中包含的资源类型（剔除下如下配置中包含的资源类型）-->      
            <excludes>  
                <exclude>**/*.yaml</exclude>  
            </excludes>  

            <!-- maven default生命周期，process-resources阶段执行maven-resources-plugin插件的resources目标处理主资源目下的资源文件时，指定处理后的资源文件输出目录，默认是${build.outputDirectory}指定的目录-->      
            <!--<targetPath>${build.outputDirectory}</targetPath> -->      

            <!-- maven default生命周期，process-resources阶段执行maven-resources-plugin插件的resources目标处理主资源目下的资源文件时，是否对主资源目录开启资源过滤 -->    
            <filtering>true</filtering>     
        </resource>  			
    </resources> 	
</build>
```