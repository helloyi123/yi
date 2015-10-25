//
//  HttpPostExecutor.h
//  HttpTest
//
//  Created by WANGJIE on 13-11-6.
//  Copyright (c) 2013年 WANGJIE. All rights reserved.
//

#import <Foundation/Foundation.h>

@interface HttpPostExecutor : NSObject<NSURLConnectionDataDelegate>
{
    NSMutableData *resultData; // 存放请求结果
    void (^finishCallbackBlock)(NSString *); // 执行完成后回调的block
    
}
@property NSMutableData *resultData;
@property(strong) void (^finishCallbackBlock)(NSString *);

+ (void)postExecuteWithUrlStr:(NSString *)urlStr Paramters:(NSString *)params FinishCallbackBlock:(void (^)(NSString *))block;

@end
