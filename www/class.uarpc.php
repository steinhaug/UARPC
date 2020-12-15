<?php
$mysql_user = "a2uthuser";
$mysql_password = "889ydfay";
$mysql_host = "localhost";
$mysql_database = "phprbac";
$mysql_port = "3306";
require 'mysqli_connect.php';

class UARPC {

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

class UARPC_RoleManager 
{
    public function __construct()
    {
        echo 'UARPC_RoleManager init: ' . time() . '<br>';
    }
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

}
class UARPC_PermissionManager 
{
    public function __construct()
    {
        echo 'UARPC_PermissionManager init: ' . time() . '<br>';
    }
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

}


define('USER_KIM', 999);

$ur = new UARPC;

echo '<hr>Functional approach:<br>';

$roleid =       $ur->addRole('Manager');
$permissionid = $ur->addPermission('/invoice/read','User can read invoice');
$ur->assignRole($roleid, USER_KIM);
$ur->assignPermission($permissionid, $roleid);

echo '<hr>Object oriented approach:<br>';

$roleid = $ur->roles->add('Manager');
$permissionid = $ur->permissions->add('/invoice/read','User can read invoice');
$ur->roles->assign($roleid, USER_KIM);
$ur->permissions->assign($permissionid, $roleid);
