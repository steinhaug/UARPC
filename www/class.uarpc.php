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

    public function __construct()
    {
        echo 'UARPC init: ' . time() . '<br>';
        $this->roles = new UARPC_RoleManager ();
        $this->permissions = new UARPC_PermissionManager ();
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

}

/**
 * All role based functions and operations goes into this class
 */
class UARPC_RoleManager 
{
    public function __construct()
    {
        echo 'UARPC_RoleManager init: ' . time() . '<br>';
    }


    /**
     * Add role by name and description
     *
     * @param string $title
     * @param string $description
     *
     * @return int On success returns RoleID
     */
    public function add($title, $description='')
    {
        global $mysqli;
        $res = $mysqli->prepared_query("SELECT RoleID from UARPC__roles WHERE title=?", 's', [$title]);
        if( !count($res) ){
            $sql = [
                "INSERT INTO UARPC__roles (`title`,`description`) VALUES (?,?)",
                "ss",
                [$title,$description]
            ];
            $result = $mysqli->prepared_insert($sql);
            echo 'Role created successfully, RoleID: ' . $result . '<br>';
            return $result;
        } else {
            echo 'Role (' . $res[0]['RoleID'] . ') already exists.<br>';
            return $res[0]['RoleID'];
        }

    }

    /**
     * Assign User to Role
     *
     * @param int $RoleID
     * @param int $UserID
     *
     * @return boolean Retuirns true on success
     */
    public function assign($RoleID, $UserID)
    {
        global $mysqli;

        $res = $mysqli->prepared_query("SELECT `UserID`,`RoleID` from UARPC__userroles WHERE UserID=? AND RoleID=?", 'ii', [$UserID,$RoleID]);
        if (!count($res)) {
            $sql = [
                "INSERT INTO UARPC__userroles (`UserID`,`RoleID`,`AssignmentDate`) VALUES (?,?,?)",
                "iii",
                [$UserID,$RoleID,time()]
            ];
            $result = $mysqli->prepared_insert($sql);
            echo 'UserID (' . $UserID . ') assigned to RoleID (' . $RoleID . ') successfully.<br>';
            return true;
        } else {
            echo 'UserID (' . $UserID . ') already assigned to RoleID (' . $RoleID . ').' . '<br>';
            return true;
        }
    }


    /**
     * Return RoleID from role
     *
     * @param string $title
     *
     * @return int
     */
    public function id($title)
    {
        global $mysqli;

        $res = $mysqli->prepared_query("SELECT RoleID from UARPC__roles WHERE title=?", 's', [$title]);
        if (!count($res)) {
            echo 'RoleID for ' . $title . ' does not exist' . '<br>';
            return false;
        } else {
            echo 'RoleId returned is ' . $res[0]['RoleID'] . '<br>';
            return $res[0]['RoleID'];
        }
    }

}

/**
 * All permission based operations and functions goes into this class.
 */class UARPC_PermissionManager 
{
    public function __construct()
    {
        echo 'UARPC_PermissionManager init: ' . time() . '<br>';
    }

    /**
     * Add permission by name and description
     *
     * @param string $title
     * @param string $description
     *
     * @return int Returns PermissionId on success
     */
    public function add($title, $description='')
    {
        global $mysqli;

        $res = $mysqli->prepared_query("SELECT PermissionID from UARPC__permissions WHERE title=?", 's', [$title]);
        if( !count($res) ){
            $sql = [
                "INSERT INTO UARPC__permissions (`title`,`description`) VALUES (?,?)",
                "ss",
                [$title,$description]
            ];
            $result = $mysqli->prepared_insert($sql);
            echo 'Permission created successfully, PermissionID: ' . $result . '<br>';
            return $result;
        } else {
            echo 'Permission (' . $res[0]['PermissionID'] . ') already exists.<br>';
            return $res[0]['PermissionID'];
        }

    }

    /**
     * Assign Role to Permission
     *
     * @param int $PermissionID
     * @param int $RoleID
     *
     * @return boolean Returns true on success
     */
    public function assign($PermissionID, $RoleID)
    {
        global $mysqli;

        $res = $mysqli->prepared_query("SELECT `RoleID`,`PermissionID` from UARPC__rolepermissions WHERE RoleID=? AND PermissionID=?", 'ii', [$RoleID,$PermissionID]);
        if (!count($res)) {
            $sql = [
                "INSERT INTO UARPC__rolepermissions (`RoleID`,`PermissionID`,`AssignmentDate`) VALUES (?,?,?)",
                "iii",
                [$RoleID,$PermissionID,time()]
            ];
            $result = $mysqli->prepared_insert($sql);
            echo 'RoleID (' . $RoleID . ') assigned to PermissionID (' . $PermissionID . ') successfully.<br>';
            return true;
        } else {
            echo 'RoleID (' . $RoleID . ') already assigned to PermissionID (' . $PermissionID . ').' . '<br>';
            return true;
        }
    }

    /**
     * Return PermissionID for permission
     *
     * @param string $title
     *
     * @return int Returns PermissionID on success
     */
    public function id($title)
    {
        global $mysqli;

        $res = $mysqli->prepared_query("SELECT PermissionID from UARPC__permissions WHERE title=?", 's', [$title]);
        if (!count($res)) {
            echo 'PermissionID for ' . $title . ' does not exist' . '<br>';
            return false;
        } else {
            echo 'PermissionID returned is ' . $res[0]['PermissionID'] . '<br>';
            return $res[0]['PermissionID'];
        }
    }

}


