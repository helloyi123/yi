//
//  WordDetailTableViewCell.m
//  Yi
//
//  Created by 孙恺 on 15/10/24.
//  Copyright © 2015年 sunkai. All rights reserved.
//

#import "WordReviewDetailTableViewCell.h"

@interface WordReviewDetailTableViewCell()

@end

@implementation WordReviewDetailTableViewCell

- (void)setDetailInfomationDescription:(NSString *)detail {
    [self.detailTextInfomation setText:detail];
}

- (void)setZanCount:(NSInteger)count {
    [self.zanButtonLabel setText:[NSString stringWithFormat:@"%li",(long)count]];
}

- (void)awakeFromNib {
    // Initialization code
}

- (void)setSelected:(BOOL)selected animated:(BOOL)animated {
    [super setSelected:selected animated:animated];
    
    // Configure the view for the selected state
}

@end
