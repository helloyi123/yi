//
//
//  POST异步请求的封装
//  使用方法，只需传入url，参数组成的字符串，真实委托对象，完成时的真实委托对象的回调方法Selector
//  如下：
//  [HttpPostWrapper postExecuteWithUrlStr:@"http://www.baidu.com"
//                                  Paramters:nil
//                                  Delegate:self
//                                  FinishCallBackSelector:@selector(postCallback:)];
//  post提交的参数，格式如下：
//  参数1名字=参数1数据&参数2名字＝参数2数据&参数3名字＝参数3数据&...
//
//  在真实委托对象中添加结果回调方法
//  - (void)postCallback:(NSString *)result
//  {
//    NSLog(@"postCallback result: %@", result);
//  }
//
//  HttpPostWrapper.m
//  HttpTest
//
//  Created by WANGJIE on 13-11-5.
//  Copyright (c) 2013年 WANGJIE. All rights reserved.
//

#import "HttpPostWrapper.h"

@implementation HttpPostWrapper
@synthesize resultData, delegate, finishCallBack;

NSString *DEFAULT_CALLBACK_SELECTOR = @"postDefaultFinishCallBack:"; // 默认的完成回调方法

/**
 * 执行POST请求
 */
+ (void)postExecuteWithUrlStr:(NSString *)urlStr Paramters:(NSString *)params Delegate:(id)delegate FinishCallBackSelector:(SEL)finishCallBack
{
    // 生成一个post请求回调委托对象（实现了<NSURLConnectionDataDelegate>协议）
    HttpPostWrapper *wrapper = [[HttpPostWrapper alloc] init];
    wrapper.delegate = delegate; // 设置真实委托对象（真正需要返回结果的那个委托对象）
    wrapper.finishCallBack = finishCallBack; // 设置真实委托对象的回调方法（post请求的结果会被返回到这个方法的参数中）
    
    NSURL *url = [NSURL URLWithString:urlStr]; // 生成NSURL对象
    // 生成Request请求对象（并设置它的缓存协议、网络请求超时配置）
    NSMutableURLRequest *request = [[NSMutableURLRequest alloc] initWithURL:url cachePolicy:NSURLRequestUseProtocolCachePolicy timeoutInterval:30];
    
//    [request setHTTPMethod:@"POST"]; // 设置为post请求
    
    [request setHTTPBody:[params dataUsingEncoding:NSUTF8StringEncoding]]; // 设置请求参数

    // 执行请求连接
    NSURLConnection *conn = [[NSURLConnection alloc] initWithRequest:request delegate:wrapper];
    
    NSLog(conn ? @"连接创建成功" : @"连接创建失败");
    
}



/**
 * 接收到服务器回应的时回调
 */
- (void)connection:(NSURLConnection *)connection didReceiveResponse:(NSURLResponse *)response
{
    NSHTTPURLResponse *resp = (NSHTTPURLResponse *)response;
    // 初始化NSMutableData对象（用于保存执行结果）
    if(!resultData){
        resultData = [[NSMutableData alloc] init];
    }else{
        [resultData setLength:0];
    }
    
    if ([response respondsToSelector:@selector(allHeaderFields)]) {
        
        NSDictionary *dictionary = [resp allHeaderFields];
        
        NSLog(@"[network]allHeaderFields:%@",[dictionary description]);
    }
    
}
/**
 * 接收到服务器传输数据的时候调用，此方法根据数据大小执行若干次
 */
- (void)connection:(NSURLConnection *)connection didReceiveData:(NSData *)data
{
    [resultData appendData:data]; // 追加结果
}
/**
 * 数据传完之后调用此方法
 */
- (void)connectionDidFinishLoading:(NSURLConnection *)connection
{
    // 把请求结果以UTF-8编码转换成字符串
    NSString *resultStr = [[NSString alloc] initWithData:[self resultData] encoding:NSUTF8StringEncoding];

    [self callBackToDelegate:resultStr];
    
}
/**
 * 网络请求过程中，出现任何错误（断网，连接超时等）会进入此方法
 */
- (void)connection:(NSURLConnection *)connection didFailWithError:(NSError *)error
{
    NSLog(@"network error: %@", [error localizedDescription]);
    
    [self callBackToDelegate:nil];
    
}
/**
 * 把结果回调给真实委托对象（如果网络发生错误，则返回结果为nil）
 */
- (void)callBackToDelegate:(NSString *)resultStr
{
    // 判断用户设置的post执行完成的回调方法是否有效
    if ([delegate respondsToSelector:finishCallBack]) {
        // 执行真实委托对象的post完成回调方法，并传入结果
        [delegate performSelector:finishCallBack withObject:resultStr];
    }else{ // 如果用户自己设置的post执行完成回调方法无效，则尝试
        NSLog(@"no such selector named: \"%s\", try to use the default callback method[%@]...",
              sel_getName(finishCallBack), DEFAULT_CALLBACK_SELECTOR);
        // 执行真实委托对象的post完成回调方法，并传入结果
        [delegate performSelector:NSSelectorFromString(DEFAULT_CALLBACK_SELECTOR) withObject:resultStr];
    }
    
}

@end
