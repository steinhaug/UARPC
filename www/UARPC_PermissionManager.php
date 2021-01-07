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
            if($this->verbose_actions) echo 'Permission created successfully, PermissionID: ' . $result . '<br>';
            return $result;
        } else {
            if($this->verbose_actions) echo 'Permission (' . $res[0]['PermissionID'] . ') already exists.<br>';
            return $res[0]['PermissionID'];
        }

    }

    /**
     * Update a permission
     *
     * @param int $PermissionID PermissionID to update
     * @param string $title Permission title
     * @param string $description Optional, permission description
     *
     * @return void
     */
    public function edit($PermissionID, $title, $description='')
    {
        global $mysqli;

        $sql = [
            "UPDATE UARPC__permissions SET `title`=?, `description`=? WHERE PermissionID=?",
            "ssi",
            [$title,$description,$PermissionID]
        ];
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

    public function list()
    {
        global $mysqli;

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
