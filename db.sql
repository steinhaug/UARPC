CREATE TABLE `userrights__userroles` (
	`UserID` INT(10) NOT NULL,
	`RoleID` INT(10) NOT NULL,
	`AssignmentDate` INT(10) NOT NULL,
	PRIMARY KEY (`UserID`, `RoleID`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_bin;

CREATE TABLE IF NOT EXISTS `userrights__rolepermissions` (
  `RoleID` int(11) NOT NULL,
  `PermissionID` int(11) NOT NULL,
  `AssignmentDate` int(11) NOT NULL,
  PRIMARY KEY  (`RoleID`,`PermissionID`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_bin;

CREATE TABLE IF NOT EXISTS `userrights__roles` (
  `RoleID` int(11) NOT NULL auto_increment,
  `title` varchar(128) NOT NULL,
  `description` text NOT NULL,
  PRIMARY KEY  (`RoleID`),
  KEY `title` (`title`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_bin;

CREATE TABLE IF NOT EXISTS `userrights__permissions` (
  `PermissionID` int(11) NOT NULL auto_increment,
  `title` char(64) NOT NULL,
  `description` text NOT NULL,
  PRIMARY KEY  (`PermissionID`),
  KEY `title` (`title`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_bin AUTO_INCREMENT=1;

CREATE TABLE IF NOT EXISTS `uarpc__useroverridepermissions` (
  `UserID` int(11) NOT NULL,
  `PermissionID` int(11) NOT NULL,
  `AssignmentDate` int(11) NOT NULL,
  PRIMARY KEY  (`UserID`,`PermissionID`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_bin;
