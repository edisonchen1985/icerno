
CREATE TABLE `room_action_log` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT COMMENT 'ID',
  `channel_id` varchar(50) NOT NULL DEFAULT '' COMMENT '频道id',
  `channel_name` varchar(50) NOT NULL DEFAULT '' COMMENT '频道名称',
  `log_type` tinyint(1) unsigned NOT NULL DEFAULT 1 COMMENT '事件的类别，1是离开房间，2是加入房间',
  `user_id` varchar(50) NOT NULL DEFAULT '' COMMENT '用户id',
  `user_name` varchar(50) NOT NULL DEFAULT '' COMMENT '用户名称',
  `log_time` int(10) unsigned NOT NULL DEFAULT 0 COMMENT '发生的时间',
  PRIMARY KEY (`id`)
) ENGINE=MyISAM AUTO_INCREMENT=16 DEFAULT CHARSET=utf8 COMMENT='用户进出房间的日志记录表';
