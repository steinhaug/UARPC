<?php

require 'UARPC_PermissionManager.php';
require 'UARPC_RoleManager.php';
require 'UARPC_UserManager.php';
require 'UARPC_base.php';

/**
 * UARPC utilities - Making sure above files are working and $mysqli is running
 * 
 * (c) Kim Steinhaug
 * http://steinhaug.no/
 * 
 * User Access, Roles, Permissions and Configurations framework Tools for administering user access.
 * Url: https://gitlab.com/steinhaug/uarpc
 * 
 */
class uarpc
{
    public function __construct()
    {
        global $mysqli;

        if( !method_exists($mysqli, 'prepared_insert') ){
            die('Fatal error, steinhaug/uarpc requires class.mysqli.php. Make sure $mysqli is setup and try again.');
        }
    }

    /**
     * utility, set up database structure needed
     *
     * @return void
     */
    public function setup()
    {
        global $mysqli;

        $collate = $mysqli->return_charset_and_collate(['utf8'=>'utf8_bin', 'utf8mb4'=>'utf8mb4_bin']);
        $tables = [];

        if(!$mysqli->table_exist('uarpc__permissions')){
            $mysqli->query("CREATE TABLE IF NOT EXISTS `uarpc__permissions` (
            `PermissionID` int NOT NULL AUTO_INCREMENT,
            `parentId` int DEFAULT NULL,
            `enabled` tinyint unsigned NOT NULL DEFAULT '1',
            `title` char(64) CHARACTER SET utf8 COLLATE " . $collate['utf8'] . " NOT NULL,
            `description` text CHARACTER SET utf8 COLLATE " . $collate['utf8'] . " NOT NULL,
            PRIMARY KEY (`PermissionID`),
            KEY `title` (`title`)
            ) ENGINE=InnoDB AUTO_INCREMENT=1 DEFAULT CHARSET=utf8 COLLATE=" . $collate['utf8']);
            $tables[] = 'uarpc__permissions';
        }
        if(!$mysqli->table_exist('uarpc__rolepermissions')){
            $mysqli->query("CREATE TABLE IF NOT EXISTS `uarpc__rolepermissions` (
            `RoleID` int NOT NULL,
            `PermissionID` int NOT NULL,
            `AssignmentDate` int NOT NULL,
            PRIMARY KEY (`RoleID`,`PermissionID`)
            ) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=" . $collate['utf8']);
            $tables[] = 'uarpc__rolepermissions';
        }
        if(!$mysqli->table_exist('uarpc__roles')){
            $mysqli->query("CREATE TABLE IF NOT EXISTS `uarpc__roles` (
            `RoleID` int NOT NULL AUTO_INCREMENT,
            `title` varchar(128) COLLATE " . $collate['utf8'] . " NOT NULL,
            `description` text COLLATE " . $collate['utf8'] . " NOT NULL,
            PRIMARY KEY (`RoleID`),
            KEY `title` (`title`)
            ) ENGINE=InnoDB AUTO_INCREMENT=1 DEFAULT CHARSET=utf8 COLLATE=" . $collate['utf8']);
            $tables[] = 'uarpc__roles';
        }
        if(!$mysqli->table_exist('uarpc__userallowpermissions')){
            $mysqli->query("CREATE TABLE IF NOT EXISTS `uarpc__userallowpermissions` (
            `UserID` int NOT NULL,
            `PermissionID` int NOT NULL,
            `AssignmentDate` int NOT NULL,
            PRIMARY KEY (`UserID`,`PermissionID`) USING BTREE
            ) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=" . $collate['utf8']);
            $tables[] = 'uarpc__userallowpermissions';
        }
        if(!$mysqli->table_exist('uarpc__userdenypermissions')){
            $mysqli->query("CREATE TABLE IF NOT EXISTS `uarpc__userdenypermissions` (
            `UserID` int NOT NULL,
            `PermissionID` int NOT NULL,
            `AssignmentDate` int NOT NULL,
            PRIMARY KEY (`UserID`,`PermissionID`)
            ) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=" . $collate['utf8']);
            $tables[] = 'uarpc__userdenypermissions';
        }
        if(!$mysqli->table_exist('uarpc__userroles')){
            $mysqli->query("CREATE TABLE IF NOT EXISTS `uarpc__userroles` (
            `UserID` int NOT NULL,
            `RoleID` int NOT NULL,
            `AssignmentDate` int NOT NULL,
            PRIMARY KEY (`UserID`,`RoleID`)
            ) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=" . $collate['utf8']);
            $tables[] = 'uarpc__userroles';
        }

        if( count($tables) ){
            echo count($tables) . ' tables created, UARPC ready.';
        }

    }

}