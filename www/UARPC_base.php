<?php

/**
 * UARPC - User Access Roles Permissions Configuration v1.6.0
 *
 * Framework for managing and checking all rights for a particular user.
 *
 * Maintained by: @steinhaug
 *
 * Roadmap:
 * - user override of the roles, this way we can tailer all needs
 * - configuration object extends a permission
 * - optimazation of DB and queries making sure it's fast
 *
 */
class UARPC_base
{
    public $roles;
    public $permissions;
    public $users;

    // default UserID
    public $UserID = null;

    // Database prefix
    public $db_prefix = 'uarpc_';

    // if true will output lots of debugging info
    public $verbose_actions = false;

    public function __construct($UserID = null, $verbose_actions = false, $db_prefix = null)
    {
        $this->verbose_actions = $verbose_actions;

        if (isset($GLOBALS['steinhaugUarpcDbPrefix'])) {
            $this->db_prefix = $GLOBALS['steinhaugUarpcDbPrefix'];
        }

        if ($db_prefix !== null) {
            $this->db_prefix = $db_prefix;
        }

        if ($this->verbose_actions) {
            echo 'UARPC init: ' . time() . '<br>';
        }
        if ($UserID !== null) {
            $this->UserID = $UserID;
            if ($this->verbose_actions) {
                echo 'UserID set for ' . $this->UserID . '<br>';
            }
        }

        $this->roles = new UARPC_RoleManager($this->UserID, $this->verbose_actions, $this->db_prefix);
        $this->permissions = new UARPC_PermissionManager($this->UserID, $this->verbose_actions, $this->db_prefix);
        $this->users = new UARPC_UserManager($this->UserID, $this->verbose_actions, $this->db_prefix);
    }

    public function set_db_prefix($db_prefix)
    {
        $this->db_prefix = $db_prefix;
    }

    public function addRole($title, $description = '')
    {
        return $this->roles->add($title, $description);
    }

    public function addPermission($title, $description = '')
    {
        return $this->permissions->add($title, $description);
    }

    public function assignRole($RoleID, $UserID)
    {
        return $this->roles->assign($RoleID, $UserID);
    }

    public function assignPermission($PermissionID, $RoleID)
    {
        return $this->permissions->assign($PermissionID, $RoleID);
    }


    /**
     * Check if user have permission
     *
     * Permission needs to be enabled, not denied or assigned to user as overrid, or finally belonging to the role
     *
     * @param string $PermissionTitle Permission name
     * @param string $UserID UserID to check for permission
     *
     * @return int Returns 0 if no persmission or >0 if permitted
     */
    public function havePermission($PermissionTitle, $UserID = null)
    {
        global $mysqli;

        if ($UserID === null and $this->UserID === null) {
            throw new Exception('No UserID');
        }

        if ($UserID === null)
            $UserID = $this->UserID;


        // 1. check if permissions is enabled
        if (!$this->permEnabled($PermissionTitle)) {
            return false;
        }

        // 2. check if permission is denied on user
        $sql = 'SELECT * 
                FROM `' . $this->db_prefix . '_permissions` `up` 
                JOIN `' . $this->db_prefix . '_userdenypermissions` `uudp` ON ( `uudp`.`PermissionID` = `up`.`PermissionID` ) 
                WHERE `up`.`title`=? AND `uudp`.`UserID` = ?
                ';
        $res = $mysqli->execute1($sql, 'si', [$PermissionTitle, $UserID], true);
        if ($res !== null) {
            if ($this->verbose_actions) {
                echo '[-] User(' . $UserID . ') is denied permission \'' . $PermissionTitle . '\' as override<br>';
            }
            return false;
        }

        // 3. Check if permission is allowed for user
        $sql = 'SELECT * 
                FROM `' . $this->db_prefix . '_permissions` `up` 
                JOIN `' . $this->db_prefix . '_userallowpermissions` `uudp` ON ( `uudp`.`PermissionID` = `up`.`PermissionID` ) 
                WHERE `up`.`title`=? AND `uudp`.`UserID` = ?
                ';
        $res = $mysqli->execute1($sql, 'si', [$PermissionTitle, $UserID], true);
        if ($res !== null) {
            if ($this->verbose_actions) {
                echo '[OK] User(' . $UserID . ') is allowed permission \'' . $PermissionTitle . '\' as override<br>';
            }
            return true;
        }

        // 4. Check if permission is allowed for role belonging to user
        $sql = 'SELECT * 
                FROM `' . $this->db_prefix . '_permissions` `up` 
                JOIN `' . $this->db_prefix . '_rolepermissions` `urp` ON ( `urp`.`PermissionID` = `up`.`PermissionID` ) 
                JOIN `' . $this->db_prefix . '_userroles` `uur` ON ( `uur`.`RoleID` = `urp`.`RoleID` ) 
                JOIN `' . $this->db_prefix . '_roles` `ur` ON ( `ur`.`RoleID` = `uur`.`RoleID` ) 
                WHERE `up`.`title`=? AND `uur`.`UserID` = ?
                ';

        $res = $mysqli->execute1($sql, 'si', [$PermissionTitle, $UserID], true);
        if ($res !== null) {
            if ($this->verbose_actions) {
                echo '[OK] User(' . $UserID . ') is allowed permission \'' . $PermissionTitle . '\' from roles<br>';
            }
            return true;
        }


        if ($this->verbose_actions) {
            echo '[-] User(' . $UserID . ') do not have permission \'' . $PermissionTitle . '\' from roles<br>';
        }
        // User is not permitted
        return false;
    }


    /**
     * Check if a module is enabled from system perspective
     *
     * @param string $PermissionTitle
     *
     * @return bool Returns true for enabled and active modules, false for disabled or not enabled ones
     */
    public function permEnabled($PermissionTitle)
    {
        global $mysqli;

        $res = $mysqli->execute1("SELECT * FROM `" . $this->db_prefix . "_permissions` WHERE `title`=?", 's', [$PermissionTitle], true);

        if ($res !== null) {
            if ($this->verbose_actions) {
                echo 'permEnabled(' . $res['enabled'] . ') returned for ". $PermissionTitle . "<br>';
            }
            return boolval($res['enabled']);
        } else {
            if ($this->verbose_actions) {
                echo 'permEnabled() error, permission does not exist:  ". $PermissionTitle . "<br>';
            }
            return false;
        }
    }
}
