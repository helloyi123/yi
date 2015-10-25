//
//  POST异步请求的封装
//  使用方法，只需传入url，参数组成的字符串，执行完成后的回调block
//  如下：
//  [HttpPostExecutor postExecuteWithUrlStr:@"http://www.baidu.com"
//                              Paramters:@""
//                    FinishCallbackBlock:^(NSString *result){  // 设置执行完成的回调block
//                        NSLog(@"finish callback block, result: %@", result);
//                    }];
//  post提交的参数，格式如下：
//  参数1名字=参数1数据&参数2名字＝参数2数据&参数3名字＝参数3数据&...
//
//
//  HttpPostExecutor.m
//  HttpTest
//
//  Created by WANGJIE on 13-11-6.
//  Copyright (c) 2013年 WANGJIE. All rights reserved.
//

#import "HttpPostExecutor.h"

@implementation HttpPostExecutor
@synthesize resultData, finishCallbackBlock;

/**
 * 执行POST请求
 */
+ (void)postExecuteWithUrlStr:(NSString *)urlStr Paramters:(NSString *)params FinishCallbackBlock:(void (^)(NSString *))block
{
    // 生成一个post请求回调委托对象（实现了<NSURLConnectionDataDelegate>协议）
    HttpPostExecutor *executorDelegate = [[HttpPostExecutor alloc] init];
    executorDelegate.finishCallbackBlock = block; // 绑定执行完成时的block

    
    NSURL *url = [NSURL URLWithString:urlStr]; // 生成NSURL对象
    // 生成Request请求对象（并设置它的缓存协议、网络请求超时配置）
    NSMutableURLRequest *request = [[NSMutableURLRequest alloc] initWithURL:url cachePolicy:NSURLRequestUseProtocolCachePolicy timeoutInterval:30];
    
    //    [request setHTTPMethod:@"POST"]; // 设置为post请求
    
    [request setHTTPBody:[params dataUsingEncoding:NSUTF8StringEncoding]]; // 设置请求参数
    
    // 执行请求连接
    NSURLConnection *conn = [[NSURLConnection alloc] initWithRequest:request delegate:executorDelegate];
    
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
    
    if (finishCallbackBlock) { // 如果设置了回调的block，直接调用
        finishCallbackBlock(resultStr);
    }

    
}
/**
 * 网络请求过程中，出现任何错误（断网，连接超时等）会进入此方法
 */
- (void)connection:(NSURLConnection *)connection didFailWithError:(NSError *)error
{
    NSLog(@"network error: %@", [error localizedDescription]);
    
    if (finishCallbackBlock) { // 如果设置了回调的block，直接调用
        finishCallbackBlock(nil);
    }
    
    
}
@end
