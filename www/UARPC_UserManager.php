<?php

/**
 * UARPC - User Manager v1.3.0
 * 
 * All role based functions and operations goes into this class
 */
class UARPC_UserManager
{

    // default UserID
    var $UserID = null;

    // if true will output lots of debugging info
    public $verbose_actions = false;

    // method name to process the final returned data from list functions
    public $returnMethod_formatter = null;

    public function __construct($UserID = null, $verbose_actions = false)
    {
        $this->verbose_actions = $verbose_actions;

        if($this->verbose_actions) echo 'UARPC_UserManager init: ' . time() . '<br>';

        if( $UserID !== null ){
            $this->UserID = $UserID;
            if($this->verbose_actions) echo 'UserID set for ' . $this->UserID . '<br>';
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
            if($this->verbose_actions) echo 'UserID (' . $UserID . ') allowed PermissionID (' . $PermissionID . ') successfully.<br>';
            return true;
        } else {
            if($this->verbose_actions) echo 'UserID (' . $UserID . ') already allowed PermissionID (' . $PermissionID . ').' . '<br>';
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
            if($this->verbose_actions) echo 'Error: permissions/unallow(' . $PermissionID . ', ' . $UserID . ') did not report any databasechange' . '<br>';
        } else {
            if($this->verbose_actions) echo 'Unallow permission(' . $PermissionID . ') for user(' . $UserID . ')<br>';
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
            if($this->verbose_actions) echo 'UserID (' . $UserID . ') denied PermissionID (' . $PermissionID . ') successfully.<br>';
            return true;
        } else {
            if($this->verbose_actions) echo 'UserID (' . $UserID . ') already denied PermissionID (' . $PermissionID . ').' . '<br>';
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
            if($this->verbose_actions) echo 'Error: permissions/undeny(' . $PermissionID . ', ' . $UserID . ') did not report any databasechange' . '<br>';
        } else {
            if($this->verbose_actions) echo 'Undenied permission(' . $PermissionID . ') for user(' . $UserID . ')<br>';
            return $affected_rows;
        }
    }


    public function list($UserID=null)
    {
        return $this->permissions($UserID);

    }

    /**
     * Get list of permissions allowed for a user
     *
     * @param int $UserID Userid to list permissions, optional or will use instantiated $UserID
     *
     * @return array Associated array of permissions, PermissionID as key and permission name as value.
     */
    public function permissions($UserID=null)
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

        if($this->verbose_actions) echo 'User(' . $UserID . ') returned a total of ' . count($permissions) . ' permissions.<br>';

        if( $this->returnMethod_formatter !== null )
            return $this->{$this->returnMethod_formatter}($permissions, __FUNCTION__);
            else
            return $permissions;

    }

    /**
     * List roles belonging to user
     *
     * @param int $UserID Optional, UserID to lookup
     *
     * @return array List of roles connected to the user
     */
    public function roles($UserID=null)
    {
        global $mysqli;

        if( $UserID === null )
            $UserID = $this->UserID;

        $sql = "SELECT * FROM `uarpc__userroles` `ur` 
                JOIN `uarpc__roles` `r` ON `r`.`RoleID` = `ur`.`RoleID` 
                WHERE `ur`.`UserID` = ?";
        $res = $mysqli->prepared_query($sql, 'i', [$UserID]);

        $roles = [];
        if (count($res)) {
            foreach($res as $row){
                $roles[$row['RoleID']] = [
                                            'RoleID' => $row['RoleID'],
                                            'title' => $row['title'],
                                            'description' => $row['description']
                                         ];
            }
        }

        if( $this->returnMethod_formatter !== null )
            return $this->{$this->returnMethod_formatter}($roles, __FUNCTION__);
            else
            return $roles;
    }

    /**
     * Chainable formatter setting
     *
     * @param string $format Name of format to be recieved
     *
     * @return void
     */
    public function format($format)
    {
        $valid_formatters = [','];
        if( !in_array($format, $valid_formatters) )
            die('<h1>$UARPC error - Must be fixed!</h1><div>-&gt;format(formatter) error: &quot;' . $format . '&quot;.<br>Possible formatters are: &quot;' . implode('&quot;, &quot;', $valid_formatters) . '&quot;</div>');

        if($format == ',')
            $this->returnMethod_formatter = 'format__comma_seperated';

        return $this;
    }

    /**
     * Formatter function: return str, str, str, str
     *
     * @param mixed $data The data normally returned by $UARPC
     * @param string $caller_method Method name data comes from
     *
     * @return mixed The processed data
     */
    public function format__comma_seperated($data, $caller_method)
    {

        $this->returnMethod_formatter = null;
        if( $caller_method == 'roles' ){
            $txt = '';
            foreach($data as $key=>$val){
                if(mb_strlen($txt))
                    $txt .= ', ';
                $txt .= $val['title'];
            }
            return $txt;
        } else if( $caller_method == 'permissions' ){
            return implode(', ', $data);
        }

        return 'Formatter error, unknown caller: ' . $ref;

    }

}