//
//  WordDetailTableViewCell.h
//  Yi
//
//  Created by 孙恺 on 15/10/24.
//  Copyright © 2015年 sunkai. All rights reserved.
//

#import <UIKit/UIKit.h>

@interface WordReviewDetailTableViewCell : UITableViewCell

@property (weak, nonatomic) IBOutlet UILabel *zanButtonLabel;
@property (weak, nonatomic) IBOutlet UILabel *detailTextInfomation;

- (void)setDetailInfomationDescription:(NSString *)detail;
- (void)setZanCount:(NSInteger)count;

@end
