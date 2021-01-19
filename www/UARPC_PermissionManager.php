<?php

/**
 * All permission based operations and functions goes into this class.
 */
class UARPC_PermissionManager 
{

    public $verbose_actions = false;

    public function __construct($verbose_actions = false)
    {
        $this->verbose_actions = $verbose_actions;

        if($this->verbose_actions) echo 'UARPC_PermissionManager init: ' . time() . '<br>';
    }

    /**
     * Add permission by name and description
     *
     * @param string $title
     * @param string $description
     * @param int $enabled Boolval for stating if a permission is enabled or valid from a system perspective
     *
     * @return int Returns PermissionId on success
     */
    public function add($title, $description='', $enabled = 1)
    {
        global $mysqli;

        $res = $mysqli->prepared_query("SELECT PermissionID from UARPC__permissions WHERE title=?", 's', [$title]);
        if( !count($res) ){
            $sql = [
                "INSERT INTO UARPC__permissions (`enabled`,`title`,`description`) VALUES (?,?,?)",
                "iss",
                [boolval($enabled)?1:0,$title,$description]
            ];
            $result = $mysqli->prepared_insert($sql);
            if($this->verbose_actions) echo 'Permission created successfully, PermissionID: ' . $result . '<br>';
            return $result;
        } else {
            if($this->verbose_actions) echo 'Permission (' . $res[0]['PermissionID'] . ') already exists.<br>';
            return $res[0]['PermissionID'];
        }

    }

    public function delete($PermissionID){
        global $mysqli;
        $res = $mysqli->prepared_query("DELETE FROM UARPC__permissions WHERE PermissionID=?", 'i', [$PermissionID]);
        return $res;
    }

    /**
     * Update a permission
     *
     * @param int $PermissionID PermissionID to update
     * @param string $title Permission title
     * @param string $description Optional, permission description
     * @param mixed $enabled Optional, if other than null will update state
     *
     * @return void
     */
    public function edit($PermissionID, $title, $description='', $enabled = null)
    {
        global $mysqli;

        if( $enabled !== null ){
            $sql = [
                "UPDATE UARPC__permissions SET `enabled`=?, `title`=?, `description`=? WHERE PermissionID=?",
                "issi",
                [boolval($enabled)?1:0,$title,$description,$PermissionID]
            ];
        } else {
            $sql = [
                "UPDATE UARPC__permissions SET `title`=?, `description`=? WHERE PermissionID=?",
                "ssi",
                [$title,$description,$PermissionID]
            ];
        }

        $affected_rows = $mysqli->prepared_insert($sql);
        if($affected_rows){
            if($this->verbose_actions) echo 'Permission (' . $PermissionID . ') was successfully updated.<br>';
            return true;
        } else {
            if($this->verbose_actions) echo 'Permission (' . $PermissionID . ') was NOT updated.<br>';
            return false;
        }

    }

    /**
     * Return the permission state
     *
     * @param int $PermissionID PermissionID to check
     *
     * @return bool Returns true for a valid and enabled permission, false otherwise
     */
    public function state($PermissionID)
    {
        global $mysqli;

        $sql = 'SELECT * 
                FROM uarpc__permissions up 
                WHERE up.PermissionID=?
                ';
        $res = $mysqli->prepared_query($sql, 'i', [$PermissionID]);
        if (count($res)) {
            if($this->verbose_actions) echo 'Permission (' . $PermissionID . ') state is: ' . $res[0]['enabled'] . '<br>';
            return boolval($res[0]['enabled']);
        } else {
            if($this->verbose_actions) echo 'Permission (' . $PermissionID . ') state is: error, does not exist<br>';
            return false;
        }

    }

    /**
     * Set permission state to enabled / active
     *
     * @param int $PermissionID PermissionID you want to update
     *
     * @return bool True for successfull update, else false
     */
    public function enable($PermissionID)
    {
        global $mysqli;
        $sql = [
            "UPDATE UARPC__permissions SET `enabled`=? WHERE PermissionID=?",
            "ii",
            [1,$PermissionID]
        ];
        $affected_rows = $mysqli->prepared_insert($sql);
        if($affected_rows){
            if($this->verbose_actions) echo 'Permission (' . $PermissionID . ') is enabled.<br>';
            return true;
        } else {
            if($this->verbose_actions) echo 'Permission (' . $PermissionID . ') could not be enabled.<br>';
            return false;
        }
    }

    /**
     * Set permission state to disabled / not active
     *
     * @param int $PermissionID PermissionID you want to update
     *
     * @return bool True for successfull update, else false
     */
    public function disable($PermissionID)
    {
        global $mysqli;
        $sql = [
            "UPDATE UARPC__permissions SET `enabled`=? WHERE PermissionID=?",
            "ii",
            [0,$PermissionID]
        ];
        $affected_rows = $mysqli->prepared_insert($sql);
        if($affected_rows){
            if($this->verbose_actions) echo 'Permission (' . $PermissionID . ') is now disabled.<br>';
            return true;
        } else {
            if($this->verbose_actions) echo 'Permission (' . $PermissionID . ') could not be disabled.<br>';
            return false;
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
            if($this->verbose_actions) echo 'RoleID (' . $RoleID . ') assigned to PermissionID (' . $PermissionID . ') successfully.<br>';
            return true;
        } else {
            if($this->verbose_actions) echo 'RoleID (' . $RoleID . ') already assigned to PermissionID (' . $PermissionID . ').' . '<br>';
            return true;
        }
    }

    public function unassign($PermissionID, $RoleID)
    {
        global $mysqli;

        $affected_rows = $mysqli->prepared_query("DELETE FROM UARPC__rolepermissions WHERE RoleID=? AND PermissionID=?", 'ii', [$RoleID,$PermissionID]);
        if (!$affected_rows) {
            if($this->verbose_actions) echo 'Error: permissions/unassign(' . $RoleID . ', ' . $UserID . ') did not report any databasechange' . '<br>';
            return $affected_rows;
        } else {
            if($this->verbose_actions) echo 'Unnasigned permission(' . $PermissionID . '), from role(' . $RoleID . ')<br>';
            return $affected_rows;
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
            if($this->verbose_actions) echo 'PermissionID for ' . $title . ' does not exist' . '<br>';
            return false;
        } else {
            if($this->verbose_actions) echo 'PermissionID returned is ' . $res[0]['PermissionID'] . '<br>';
            return $res[0]['PermissionID'];
        }
    }

    /**
    * List all allowed permissions
    *
    * @return array Returns all of the enabled $PermissionsIDs  
    */
    public function list($RoleID = null)
    {
        global $mysqli;

        if( $RoleID !== null ){

            $sql = 'SELECT up.PermissionID, up.title, up.description 
                    FROM uarpc__permissions up 
                    JOIN uarpc__rolepermissions urp ON ( urp.PermissionID = up.PermissionID ) 
                    JOIN uarpc__roles ur ON ( ur.RoleID = urp.RoleID ) 
                    WHERE ur.RoleID = ?
                    ';
            $res = $mysqli->prepared_query($sql, 'i', [$RoleID]);

            $items = [];
            if (count($res)) {
                foreach($res as $item){
                    $items[ $item['PermissionID'] ] = $item;
                }
            }
            return $items;

        } else {

            $res = $mysqli->query("SELECT `PermissionID`, `title`, `description` from UARPC__permissions");
            if( $res->num_rows ){
                $roles = [];
                while( $row = $res->fetch_assoc() ){
                    $roles[ $row['PermissionID'] ] = $row;
                }
                return $roles;
            } else {
                return [];
            }
   
        }
    }


    /**
     * Return permission title
     *
     * @param int $PermissionID
     *
     * @return string Permission title
     */
    public function getTitle($PermissionID)
    {
        global $mysqli;
        $res = $mysqli->prepared_query("SELECT `title` from UARPC__permissions WHERE PermissionID=?", 'i', [$PermissionID]);
        if (!count($res)) {
            if($this->verbose_actions) echo 'Permission(' . $PermissionID . ') does not exist' . '<br>';
            return false;
        } else {
            if($this->verbose_actions) echo 'Permission title returned is ' . $res[0]['title'] . '<br>';
            return $res[0]['title'];
        }
    }

    /**
     * Return permission description
     *
     * @param int $PermissionID
     *
     * @return string Permission description
     */
    public function getDescription($PermissionID)
    {
        global $mysqli;
        $res = $mysqli->prepared_query("SELECT `description` from UARPC__permissions WHERE PermissionID=?", 'i', [$PermissionID]);
        if (!count($res)) {
            if($this->verbose_actions) echo 'Permission(' . $PermissionID . ') does not exist' . '<br>';
            return false;
        } else {
            if($this->verbose_actions) echo 'Permission description returned is ' . $res[0]['description'] . '<br>';
            return $res[0]['description'];
        }
    }

}
