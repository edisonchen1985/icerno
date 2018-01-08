
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


#RocketChat文件和文件夹操作管理表
CREATE TABLE `tm_room_names` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT COMMENT 'ID',
  `channel_id` varchar(50) NOT NULL DEFAULT '' COMMENT 'TM频道id',
  `create_time` int(10) unsigned NOT NULL DEFAULT 0 COMMENT '创建的时间',
  PRIMARY KEY (`id`)
) ENGINE=MyISAM AUTO_INCREMENT=16 DEFAULT CHARSET=utf8 COMMENT='对应于TM的房间ID存储表';

#文件夹操作管理表
CREATE TABLE `tm_folder_names` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT COMMENT 'ID',
  `name` varchar(50) NOT NULL DEFAULT '' COMMENT '文件夹的名称',
  `room_id` varchar(50) NOT NULL DEFAULT '' COMMENT '对应tm_room_names的主键id',
  `create_time` int(10) unsigned NOT NULL DEFAULT 0 COMMENT '创建的时间',
  PRIMARY KEY (`id`)
) ENGINE=MyISAM AUTO_INCREMENT=16 DEFAULT CHARSET=utf8 COMMENT='文件夹管理存储表';

#文件操作管理表
CREATE TABLE `tm_file_names` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT COMMENT 'ID',
  `name` varchar(50) NOT NULL DEFAULT '' COMMENT '文件的名称',
  `folder_id` varchar(50) NOT NULL DEFAULT '' COMMENT '对应tm_folder_names的主键id',
  `create_time` int(10) unsigned NOT NULL DEFAULT 0 COMMENT '创建的时间',
  PRIMARY KEY (`id`)
) ENGINE=MyISAM AUTO_INCREMENT=16 DEFAULT CHARSET=utf8 COMMENT='文件存储表';