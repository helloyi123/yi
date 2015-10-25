//
//  HttpPostWrapper.h
//  HttpTest
//
//  Created by WANGJIE on 13-11-5.
//  Copyright (c) 2013年 WANGJIE. All rights reserved.
//

#import <Foundation/Foundation.h>

@interface HttpPostWrapper : NSObject<NSURLConnectionDataDelegate>
{
    NSMutableData *resultData; // 存放请求结果
    id delegate; // 真实委托对象
    SEL finishCallBack; // 真实委托对象的完成回调方法
}

+ (void)postExecuteWithUrlStr:(NSString *)urlStr Paramters:(NSString *)params Delegate:(id)delegate FinishCallBackSelector:(SEL)finishCallBack;


@property NSMutableData *resultData;
@property id delegate;
@property SEL finishCallBack;
@end
