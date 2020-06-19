<?php

/*
-----------------------------------------------------------
CREATE TABLE [dbo].[access_admin_option](
	[login_id] [varchar](32) NOT NULL,
	[option_name] [varchar](60) NOT NULL,
	[option_value] [varchar](60) NOT NULL,
	[reg_date] [datetime] NOT NULL
)
 -----------------------------------------------------------
CREATE TABLE [dbo].[access_admin](
	[login_id] [varchar](32) NOT NULL,
	[login_pw] [varchar](60) NOT NULL,
	[login_name] [varchar](60) NOT NULL,
	[is_use] [char](1) NOT NULL,
	[reg_date] [datetime] NOT NULL,
	[update_date] [datetime] NOT NULL,
	[last_login_date] [datetime] NOT NULL,
	[last_token] [varchar](100) NOT NULL
) ON [PRIMARY]
GO

CREATE CLUSTERED INDEX [ClusteredIndex-20160113-190559] ON [dbo].[access_admin]
(
	[login_id] ASC
)WITH (PAD_INDEX = OFF, STATISTICS_NORECOMPUTE = OFF, SORT_IN_TEMPDB = OFF, DROP_EXISTING = OFF, ONLINE = OFF, ALLOW_ROW_LOCKS = ON, ALLOW_PAGE_LOCKS = ON) ON [PRIMARY]
GO
 -----------------------------------------------------------
CREATE TABLE [dbo].[access_admin_log](
	[access_admin_log_idx] [int] IDENTITY(1,1) NOT NULL,
	[access_admin_log_idx_group] [int] NOT NULL,
	[access_admin_id] [varchar](32) NOT NULL,
	[access_admin_token] [varchar](60) NOT NULL,
	[access_ip] [varchar](50) NOT NULL,
	[access_agent_type] [varchar](20) NOT NULL,
	[access_agent_full] [varchar](300) NOT NULL,
	[access_url] [varchar](300) NOT NULL,
	[reg_time] [datetime] NOT NULL,
	[access_type] [varchar](60) NOT NULL,
 CONSTRAINT [access_admin_log_idx] PRIMARY KEY CLUSTERED 
(
	[access_admin_log_idx] ASC
)WITH (PAD_INDEX = OFF, STATISTICS_NORECOMPUTE = OFF, IGNORE_DUP_KEY = OFF, ALLOW_ROW_LOCKS = ON, ALLOW_PAGE_LOCKS = ON) ON [PRIMARY]
) ON [PRIMARY]

GO

SET ANSI_PADDING OFF
GO

ALTER TABLE [dbo].[access_admin_log] ADD  DEFAULT ('') FOR [access_type]
 *  */
