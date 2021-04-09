<?php

/**
 * UARPC - User Access Roles Permissions Configuration v1.3.0
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
class UARPC_base {

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

        if( isset($GLOBALS['steinhaugUarpcDbPrefix']) )
            $this->db_prefix = $GLOBALS['steinhaugUarpcDbPrefix'];

        if( $db_prefix !== null )
            $this->db_prefix = $db_prefix;

        if($this->verbose_actions) echo 'UARPC init: ' . time() . '<br>';
        if( $UserID !== null ){
            $this->UserID = $UserID;
            if($this->verbose_actions) echo 'UserID set for ' . $this->UserID . '<br>';
        }

        $this->roles = new UARPC_RoleManager ($this->UserID, $this->verbose_actions, $this->db_prefix);
        $this->permissions = new UARPC_PermissionManager ($this->UserID, $this->verbose_actions, $this->db_prefix);
        $this->users = new UARPC_UserManager ($this->UserID, $this->verbose_actions, $this->db_prefix);

    }

    public function set_db_prefix($db_prefix)
    {
        $this->db_prefix = $db_prefix;
    }

    public function addRole($title, $description='')
    {
        return $this->roles->add($title, $description);
    }

    public function addPermission($title, $description='')
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
     *
     * @return int Returns 0 if no persmission or >0 if permitted
     */
    public function havePermission($PermissionTitle)
    {
        global $mysqli;
        if($this->UserID === null)
            throw new Exception('No UserID');

        // 1. check if permissions is enabled
        if( !$this->permEnabled($PermissionTitle) )
            return false;

        // 2. check if permission is denied on user
        $sql = 'SELECT * 
                FROM `' . $this->db_prefix . '_permissions` `up` 
                JOIN `' . $this->db_prefix . '_userdenypermissions` `uudp` ON ( `uudp`.`PermissionID` = `up`.`PermissionID` ) 
                WHERE `up`.`title`=? AND `uudp`.`UserID` = ?
                ';
        $res = $mysqli->prepared_query($sql, 'si', [$PermissionTitle, $this->UserID]);
        if (count($res)) {
            if($this->verbose_actions) echo 'User(' . $this->UserID . ') is denied permission \'' . $PermissionTitle . '\' as override<br>';
            return false;
        }

        // 3. Check if permission is allowed for user
        $sql = 'SELECT * 
                FROM `' . $this->db_prefix . '_permissions` `up` 
                JOIN `' . $this->db_prefix . '_userallowpermissions` `uudp` ON ( `uudp`.`PermissionID` = `up`.`PermissionID` ) 
                WHERE `up`.`title`=? AND `uudp`.`UserID` = ?
                ';
        $res = $mysqli->prepared_query($sql, 'si', [$PermissionTitle, $this->UserID]);
        if (count($res)) {
            if($this->verbose_actions) echo 'User(' . $this->UserID . ') is allowed permission \'' . $PermissionTitle . '\' as override<br>';
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

        $res = $mysqli->prepared_query($sql, 'si', [$PermissionTitle, $this->UserID]);
        if (count($res)) {
            if($this->verbose_actions) echo 'User(' . $this->UserID . ') is allowed permission \'' . $PermissionTitle . '\' from roles<br>';
            return true;
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

        $sql = 'SELECT * 
                FROM `' . $this->db_prefix . '_permissions` `up` 
                WHERE `up`.`title`=?
                ';
        $res = $mysqli->prepared_query($sql, 's', [$PermissionTitle]);

        if (count($res)) {
            if($this->verbose_actions) echo 'permEnabled(' . $res[0]['enabled'] . ') returned for \'' . $PermissionTitle . '\'<br>';
            return boolval($res[0]['enabled']);
        } else {
            if($this->verbose_actions) echo 'permEnabled() error, permission does not exist:  \'' . $PermissionTitle . '\'<br>';
            return false;
        }

    }

}
