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
    // Database prefix
    public $db_prefix = 'uarpc_';

    public function __construct($cmd = null, $cmd2 = null)
    {
        global $mysqli;

        if ($cmd === null) {
            if (!method_exists($mysqli, 'prepared_insert')) {
                die('Fatal error, steinhaug/uarpc requires class.mysqli.php. Make sure $mysqli is setup and try again.');
            }
        }

        // this would be normal behaviour, and will set the table prefix
        if ($cmd !== 'setup' and $cmd !== null) {
            $this->db_prefix = $cmd;
            $GLOBALS['steinhaugUarpcDbPrefix'] = $cmd;
        }

        if ($cmd === 'setup') {
            echo 'setup notice: Creating the UARPC tables needed...' . "\n";
            $this->setup();
            echo 'setup notice: Setting up tables compleded... continuing as normal.' . "\n";
        }

        if ($cmd === null) {
            if (!$mysqli->table_exist($this->db_prefix . '_permissions')) {
                die('Fatal error, steinhaug/uarpc cannot locate the required database tables. Run "new uarpc(\'setup\');" to automatically set up new tables.');
            }
        }
    }

    public function set_db_prefix($db_prefix)
    {
        $this->db_prefix = $db_prefix;
    }


    public function rename_tableprefix($db_prefix_existing, $db_prefix_new)
    {
        global $mysqli;
        echo 'Renaming all database tables to new prefix... ' . "<br>\n";
        echo '... checking first that all tables exist ...' . "<br>\n";

        if (!$mysqli->table_exist($db_prefix_existing . '_userroles')) {
            die('ABORT! ' . $db_prefix_existing . '_userroles table does not exist, renaming cannot continue.');
        }
        if (!$mysqli->table_exist($db_prefix_existing . '_userdenypermissions')) {
            die('ABORT! ' . $db_prefix_existing . '_userdenypermissions table does not exist, renaming cannot continue.');
        }
        if (!$mysqli->table_exist($db_prefix_existing . '_userallowpermissions')) {
            die('ABORT! ' . $db_prefix_existing . '_userallowpermissions table does not exist, renaming cannot continue.');
        }
        if (!$mysqli->table_exist($db_prefix_existing . '_roles')) {
            die('ABORT! ' . $db_prefix_existing . '_roles table does not exist, renaming cannot continue.');
        }
        if (!$mysqli->table_exist($db_prefix_existing . '_rolepermissions')) {
            die('ABORT! ' . $db_prefix_existing . '_rolepermissions table does not exist, renaming cannot continue.');
        }
        if (!$mysqli->table_exist($db_prefix_existing . '_permissions')) {
            die('ABORT! ' . $db_prefix_existing . '_permissions table does not exist, renaming cannot continue.');
        }

        $mysqli->query("RENAME TABLE `" . $db_prefix_existing . "_userroles` TO `" . $db_prefix_new . "_userroles`");
        $mysqli->query("RENAME TABLE `" . $db_prefix_existing . "_userdenypermissions` TO `" . $db_prefix_new . "_userdenypermissions`");
        $mysqli->query("RENAME TABLE `" . $db_prefix_existing . "_userallowpermissions` TO `" . $db_prefix_new . "_userallowpermissions`");
        $mysqli->query("RENAME TABLE `" . $db_prefix_existing . "_roles` TO `" . $db_prefix_new . "_roles`");
        $mysqli->query("RENAME TABLE `" . $db_prefix_existing . "_rolepermissions` TO `" . $db_prefix_new . "_rolepermissions`");
        $mysqli->query("RENAME TABLE `" . $db_prefix_existing . "_permissions` TO `" . $db_prefix_new . "_permissions`");

        if ($db_prefix_new != $db_prefix) {
            echo 'Renaming complete. Remember to update your configuration with the new prefix.' . "<br>\n";
            echo '';
        } else {
            echo 'Renaming complete.' . "<br>\n";
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

        $collate = $mysqli->return_charset_and_collate(['utf8' => 'utf8_bin', 'utf8mb4' => 'utf8mb4_bin']);
        $tables = [];

        if (!$mysqli->table_exist($this->db_prefix . '_permissions')) {
            $mysqli->query("CREATE TABLE IF NOT EXISTS `" . $this->db_prefix . "_permissions` (
            `PermissionID` int NOT NULL AUTO_INCREMENT,
            `parentId` int DEFAULT NULL,
            `enabled` tinyint unsigned NOT NULL DEFAULT '1',
            `title` char(64) CHARACTER SET utf8 COLLATE " . $collate['utf8'] . " NOT NULL,
            `description` text CHARACTER SET utf8 COLLATE " . $collate['utf8'] . " NOT NULL,
            PRIMARY KEY (`PermissionID`),
            KEY `title` (`title`)
            ) ENGINE=InnoDB AUTO_INCREMENT=1 DEFAULT CHARSET=utf8 COLLATE=" . $collate['utf8']);
            $tables[] = $this->db_prefix . '_permissions';
        }
        if (!$mysqli->table_exist($this->db_prefix . '_rolepermissions')) {
            $mysqli->query("CREATE TABLE IF NOT EXISTS `" . $this->db_prefix . "_rolepermissions` (
            `RoleID` int NOT NULL,
            `PermissionID` int NOT NULL,
            `AssignmentDate` int NOT NULL,
            PRIMARY KEY (`RoleID`,`PermissionID`)
            ) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=" . $collate['utf8']);
            $tables[] = $this->db_prefix . '_rolepermissions';
        }
        if (!$mysqli->table_exist($this->db_prefix . '_roles')) {
            $mysqli->query("CREATE TABLE IF NOT EXISTS `" . $this->db_prefix . "_roles` (
            `RoleID` int NOT NULL AUTO_INCREMENT,
            `title` varchar(128) COLLATE " . $collate['utf8'] . " NOT NULL,
            `description` text COLLATE " . $collate['utf8'] . " NOT NULL,
            PRIMARY KEY (`RoleID`),
            KEY `title` (`title`)
            ) ENGINE=InnoDB AUTO_INCREMENT=1 DEFAULT CHARSET=utf8 COLLATE=" . $collate['utf8']);
            $tables[] = $this->db_prefix . '_roles';
        }
        if (!$mysqli->table_exist($this->db_prefix . '_userallowpermissions')) {
            $mysqli->query("CREATE TABLE IF NOT EXISTS `" . $this->db_prefix . "_userallowpermissions` (
            `UserID` int NOT NULL,
            `PermissionID` int NOT NULL,
            `AssignmentDate` int NOT NULL,
            PRIMARY KEY (`UserID`,`PermissionID`) USING BTREE
            ) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=" . $collate['utf8']);
            $tables[] = $this->db_prefix . '_userallowpermissions';
        }
        if (!$mysqli->table_exist($this->db_prefix . '_userdenypermissions')) {
            $mysqli->query("CREATE TABLE IF NOT EXISTS `" . $this->db_prefix . "_userdenypermissions` (
            `UserID` int NOT NULL,
            `PermissionID` int NOT NULL,
            `AssignmentDate` int NOT NULL,
            PRIMARY KEY (`UserID`,`PermissionID`)
            ) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=" . $collate['utf8']);
            $tables[] = $this->db_prefix . '_userdenypermissions';
        }
        if (!$mysqli->table_exist($this->db_prefix . '_userroles')) {
            $mysqli->query("CREATE TABLE IF NOT EXISTS `" . $this->db_prefix . "_userroles` (
            `UserID` int NOT NULL,
            `RoleID` int NOT NULL,
            `AssignmentDate` int NOT NULL,
            PRIMARY KEY (`UserID`,`RoleID`)
            ) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=" . $collate['utf8']);
            $tables[] = $this->db_prefix . '_userroles';
        }

        if (count($tables)) {
            echo count($tables) . ' tables created, UARPC ready.';
        }
    }
}
