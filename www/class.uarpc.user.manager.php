<?php


class UARPC_UserManager
{

    var $UserID = null;

    public function __construct($UserID = null)
    {
        echo 'UARPC_UserManager init: ' . time() . '<br>';
        if( $UserID !== null ){
            $this->UserID = $UserID;
            echo 'UserID set for ' . $this->UserID . '<br>';
        }
    }



    /**
     * Assign UserID to Permission
     *
     * @param int $PermissionID
     * @param int $UserID
     *
     * @return boolean Returns true on success
     */
    public function allow($PermissionID, $UserID=null)
    {
        global $mysqli;

        if( $UserID === null ){
            $UserID = $this->UserID;
        }

        if($UserID === null)
            throw new Exception('UserManager, cannot assign permission no UserID');

        $res = $mysqli->prepared_query("SELECT `UserID`,`PermissionID` from uarpc__userallowpermissions WHERE UserID=? AND PermissionID=?", 'ii', [$UserID,$PermissionID]);
        if (!count($res)) {
            $sql = [
                "INSERT INTO uarpc__userallowpermissions (`UserID`,`PermissionID`,`AssignmentDate`) VALUES (?,?,?)",
                "iii",
                [$UserID,$PermissionID,time()]
            ];
            $result = $mysqli->prepared_insert($sql);
            echo 'UserID (' . $UserID . ') allowed PermissionID (' . $PermissionID . ') successfully.<br>';
            return true;
        } else {
            echo 'UserID (' . $UserID . ') already allowed PermissionID (' . $PermissionID . ').' . '<br>';
            return true;
        }
    }

    public function unallow($PermissionID, $UserID=null)
    {
        global $mysqli;

        if( $UserID === null ){
            $UserID = $this->UserID;
        }

        if($UserID === null)
            throw new Exception('UserManager, cannot allow permission no UserID');

        $affected_rows = $mysqli->prepared_query("DELETE FROM uarpc__userallowpermissions WHERE UserID=? AND PermissionID=?", 'ii', [$UserID,$PermissionID]);
        if (!$affected_rows) {
            echo 'Error: permissions/unallow(' . $PermissionID . ', ' . $UserID . ') did not report any databasechange' . '<br>';
        } else {
            echo 'Unallow permission(' . $PermissionID . ') for user(' . $UserID . ')<br>';
            return $affected_rows;
        }
    }



    /**
     * Assign UserID to Permission
     *
     * @param int $PermissionID
     * @param int $UserID
     *
     * @return boolean Returns true on success
     */
    public function deny($PermissionID, $UserID=null)
    {
        global $mysqli;

        if( $UserID === null ){
            $UserID = $this->UserID;
        }

        if($UserID === null)
            throw new Exception('UserManager, cannot deny permission no UserID');

        $res = $mysqli->prepared_query("SELECT `UserID`,`PermissionID` from uarpc__userdenypermissions WHERE UserID=? AND PermissionID=?", 'ii', [$UserID,$PermissionID]);
        if (!count($res)) {
            $sql = [
                "INSERT INTO uarpc__userdenypermissions (`UserID`,`PermissionID`,`AssignmentDate`) VALUES (?,?,?)",
                "iii",
                [$UserID,$PermissionID,time()]
            ];
            $result = $mysqli->prepared_insert($sql);
            echo 'UserID (' . $UserID . ') denied PermissionID (' . $PermissionID . ') successfully.<br>';
            return true;
        } else {
            echo 'UserID (' . $UserID . ') already denied PermissionID (' . $PermissionID . ').' . '<br>';
            return true;
        }
    }

    public function undeny($PermissionID, $UserID=null)
    {
        global $mysqli;

        if( $UserID === null ){
            $UserID = $this->UserID;
        }

        if($UserID === null)
            throw new Exception('UserManager, cannot deny permission no UserID');

        $affected_rows = $mysqli->prepared_query("DELETE FROM uarpc__userdenypermissions WHERE UserID=? AND PermissionID=?", 'ii', [$UserID,$PermissionID]);
        if (!$affected_rows) {
            echo 'Error: permissions/undeny(' . $PermissionID . ', ' . $UserID . ') did not report any databasechange' . '<br>';
        } else {
            echo 'Undenied permission(' . $PermissionID . ') for user(' . $UserID . ')<br>';
            return $affected_rows;
        }
    }


    /**
     * Get list of permissions allowed for a user
     *
     * @param int $UserID Userid to list permissions, optional or will use instantiated $UserID
     *
     * @return array Associated array of permissions, PermissionID as key and permission name as value.
     */
    public function list($UserID=null)
    {
        global $mysqli;
        if( $UserID === null ){
            $UserID = $this->UserID;
        }

        if($UserID === null)
            throw new Exception('UserManager, cannot deny permission no UserID');

        $sql = "SELECT up.`PermissionID`  
                FROM uarpc__permissions up 
                JOIN uarpc__userdenypermissions udp  ON ( udp.PermissionID = up.PermissionID ) 
                WHERE udp.UserID=? 
                ";

        $res = $mysqli->prepared_query($sql, 'i', [$UserID]);
        $remove_ids = [];
        if ($res) {
            foreach($res as $item){
                $remove_ids[] = $item['PermissionID'];
            }
        }

        $sql = "SELECT up.`PermissionID`, up.`title` 
                FROM uarpc__permissions up 
                JOIN uarpc__rolepermissions urp ON ( urp.PermissionID = up.PermissionID ) 
                JOIN uarpc__userroles uur ON ( uur.RoleID = urp.RoleID ) 
                WHERE uur.UserID=? 
                GROUP BY up.PermissionID 
                UNION
                SELECT up.`PermissionID`, up.`title` 
                FROM uarpc__permissions up 
                JOIN uarpc__userallowpermissions uap  ON ( uap.PermissionID = up.PermissionID ) 
                WHERE uap.UserID=? 
                ";

        $res = $mysqli->prepared_query($sql, 'ii', [$UserID,$UserID]);

        $permissions = [];

        if ($res) {
            foreach($res as $item){

                if( count($remove_ids) ){
                    if( !in_array($item['PermissionID'], $remove_ids) ){
                        $permissions[$item['PermissionID']] = $item['title'];
                    }
                } else {
                    $permissions[$item['PermissionID']] = $item['title'];
                }

            }

        }

        echo 'User(' . $UserID . ') returned a total of ' . count($permissions) . ' permissions.<br>';
        return $permissions;
    }


}