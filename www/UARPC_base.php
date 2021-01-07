<?php

/**
 * UARPC - User Access Roles Permissions Configuration v0.1.0
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

    public $UserID = null;
    public $verbose_actions = false;

    public function __construct($UserID = null, $verbose_actions = false)
    {
        $this->verbose_actions = $verbose_actions;

        if($this->verbose_actions) echo 'UARPC init: ' . time() . '<br>';
        if( $UserID !== null ){
            $this->UserID = $UserID;
            if($this->verbose_actions) echo 'UserID set for ' . $this->UserID . '<br>';
        }

        $this->roles = new UARPC_RoleManager ($this->verbose_actions);
        $this->permissions = new UARPC_PermissionManager ($this->verbose_actions);
        $this->users = new UARPC_UserManager ($this->UserID, $this->verbose_actions);

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
     * @param string $PermissionTitle Permission name
     *
     * @return int Returns 0 if no persmission or >0 if permitted
     */
    public function havePermission($PermissionTitle)
    {
        global $mysqli;
        if($this->UserID === null)
            throw new Exception('No UserID');

        // 1. check if permission is denied on user
        $sql = 'SELECT * 
                FROM uarpc__permissions up 
                JOIN uarpc__userdenypermissions uudp ON ( uudp.PermissionID = up.PermissionID ) 
                WHERE up.title=? AND uudp.UserID = ?
                ';
        $res = $mysqli->prepared_query($sql, 'si', [$PermissionTitle, $this->UserID]);
        if (count($res)) {
            if($this->verbose_actions) echo 'User(' . $this->UserID . ') is denied permission \'' . $PermissionTitle . '\' as override<br>';
            return false;
        }

        // 2. Check if permission is allowed for user
        $sql = 'SELECT * 
                FROM uarpc__permissions up 
                JOIN uarpc__userallowpermissions uudp ON ( uudp.PermissionID = up.PermissionID ) 
                WHERE up.title=? AND uudp.UserID = ?
                ';
        $res = $mysqli->prepared_query($sql, 'si', [$PermissionTitle, $this->UserID]);
        if (count($res)) {
            if($this->verbose_actions) echo 'User(' . $this->UserID . ') is allowed permission \'' . $PermissionTitle . '\' as override<br>';
            return true;
        }

        // 3. Check if permission is allowed for role belonging to user
        $sql = 'SELECT * 
                FROM uarpc__permissions up 
                JOIN uarpc__rolepermissions urp ON ( urp.PermissionID = up.PermissionID ) 
                JOIN uarpc__userroles uur ON ( uur.RoleID = urp.RoleID ) 
                JOIN uarpc__roles ur ON ( ur.RoleID = uur.RoleID ) 
                WHERE up.title=? AND uur.UserID = ?
                ';

        $res = $mysqli->prepared_query($sql, 'si', [$PermissionTitle, $this->UserID]);
        if (count($res)) {
            if($this->verbose_actions) echo 'User(' . $this->UserID . ') is allowed permission \'' . $PermissionTitle . '\' from roles<br>';
            return true;
        }

        // User is not permitted
        return false;
    }

}
