# Yi App “Yi词，真（即）有Yi思！”
用户可以自行上传一个新词并为之释义，其他用户同样可以自由的为之释义，为其创造新的释义。
更为简易，更为详细，更加从多角度来释义。

## 项目描述


### 产品构思：

在已有目前的市场下，对于词语的解释目前只有通过搜索或者百度百科相关来查阅新词汇的解译，
但是在目前日益快速发展的网络社会目前通过百度百科或者说通过一些其它的媒体来查阅词语已经过于缓慢或者跟不上查阅速度。
例如说，网络新潮词汇，专业术语等相关词语目前尚未有相关途径来查阅了解，所以这就会造成一个很大的问题与需求
百度百科的词语创造与解译过于详细与复杂，用户在已有碎片化的时间内无法花大量时间去阅读与理解，也无法的去快速创造词语解释，所以我们希望能通过用户的评论短评来**快速**，**有趣**，**多方面**角度的来了解这个词语的解译。


在上述情况下，我们还考虑了在已有的现有市场下，对于其它用户人群我们也是需要的。
例如我早前认识的一位朋友，对于新词的创造是颇有意义的。
例如说，她是一位宠物师，但是在已有的很多的宠物专业术语或者相关知识无法快速传播,在新兴职业的发展下，新的行业的相关知识与介绍我们无法从已有平台来发现，所以我们希望能在我们平台
通过创造新兴新词，让新兴行业的知识传播更有效率，更为全面。


### 应用场景：

#### 我们把用户人群分为以下几类人群

1.因目前在现有平台无法查阅到的词语与知识，希望通过我们快速了解新词的解译。

2.希望能通过我们的产品了解关于词语的其它解释，更为快速，有效，有趣,利用碎片化时间来了解。

3.对于新兴词语的创造以及有效推广相关行业的相关知识。

4.目前我们尚未了解的相关应用场景，我们相信还有其它的应用场景我们尚未发现。


> 朋友之间聊天却不知道他说的新潮词汇是什么梗？  
> 心中看法与常人有异，却因为怕被认识做不合群不敢说出？  
> 一千个人，却只能看到一个莎士比亚？  
> 你是否也这样，因为无知而人云亦云。  
> 因为人云亦云，而泯然众人矣。  
> 打开解词有Yi，取回聊天主动权；  
> 打开解词有Yi，大胆说出你的与众不同；  
> 打开解词有Yi，不让你的孩子只能在书本上看到“一千个人就有一千个莎士比亚”这句话。  


## 技术实现

### 服务端(on branch server)

#### 架构
Mysql + PHP + NGINX

### 前台RESTful API

1. 使用基于HTTP的json格式传输数据，相比xml更加节省传输流量。并且更加的易读，容易解析。
2. 使用bephp/router这个使用树结构存储路由的路由控制器。解析路由的速度更快。
3. 使用bephp/activerecord这个模型来访问数据库，更加的方便，易于生成RESTful的API。

### 管理后台
4. 使用bephp/microtpl这基于template attribute template实现的模板控制器，更严格的检查模板语法。不至于出错。
5. 使用bootstrap生成后端页模板，方便快捷。



### 客户端 (on branch master)

#### IOS 

##### 开发环境
1. Xcode 7.0
2. iOS SDK 9.0

##### 第三方库
1. ShareSDK

##### 技术细节
1. NSJSONSerilzation序列化数据
2. NSURLSession网络访问

## 团队成员

* ken zhou
* [Lloyd Zhou](https://github.com/lloydzhou)
* black su
* [talisk](http://blog.talisk.cn/)
* zhen han

## 产品预览
![image](https://raw.githubusercontent.com/helloyi123/yi/master/index.jpg)
![image](https://raw.githubusercontent.com/helloyi123/yi/master/second.jpg)
![image](https://raw.githubusercontent.com/helloyi123/yi/master/notice.jpg)
