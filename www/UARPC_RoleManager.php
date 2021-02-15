<?php

/**
 * UARPC - Role Manager v1.3.0
 * 
 * All role based functions and operations goes into this class
 */
class UARPC_RoleManager 
{

    // default UserID
    var $UserID = null;

    public $verbose_actions = false;

    public function __construct($UserID = null, $verbose_actions = false)
    {
        $this->verbose_actions = $verbose_actions;

        if($this->verbose_actions) echo 'UARPC_RoleManager init: ' . time() . '<br>';

        if( $UserID !== null ){
            $this->UserID = $UserID;
            if($this->verbose_actions) echo 'UserID set for ' . $this->UserID . '<br>';
        }

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
            if($this->verbose_actions) echo 'Role created successfully, RoleID: ' . $result . '<br>';
            return $result;
        } else {
            if($this->verbose_actions) echo 'Role (' . $res[0]['RoleID'] . ') already exists.<br>';
            return $res[0]['RoleID'];
        }

    }

    /**
     * Delete a role from DB
     *
     * @param int $RoleID The role you want to delete, by ID
     *
     * @return boolean True if success and false if failure
     */
    public function delete($RoleID){
        global $mysqli;

        if ($mysqli->prepared_query1("SELECT count(*) as `count` FROM `uarpc__rolepermissions` WHERE `RoleID`=?", 'i', [$RoleID], 0)){
            if($this->verbose_actions) echo 'Delete role (' . $RoleID . ') error, connected to permissions.<br>';
            return false;
        }

        $res = $mysqli->prepared_query("DELETE FROM `UARPC__roles` WHERE `RoleID`=?", 'i', [$RoleID]);
        return $res;
    }

    /**
     * Update a role
     *
     * @param int $RoleID RoleID to update
     * @param string $title Role title
     * @param string $description Optional, role description
     *
     * @return void
     */
    public function edit($RoleID, $title, $description='')
    {
        global $mysqli;

        $sql = [
            "UPDATE UARPC__roles SET `title`=?, `description`=? WHERE RoleID=?",
            "ssi",
            [$title,$description,$RoleID]
        ];
        $affected_rows = $mysqli->prepared_insert($sql);
        if($affected_rows){
            if($this->verbose_actions) echo 'Role (' . $RoleID . ') was successfully updated.<br>';
            return true;
        } else {
            if($this->verbose_actions) echo 'Role (' . $RoleID . ') was NOT updated.<br>';
            return false;
        }

    }



    /**
     * Assign User to Role
     *
     * @param int $RoleID
     * @param int $UserID
     *
     * @return boolean Returns true on success
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
            if($this->verbose_actions) echo 'UserID (' . $UserID . ') assigned to RoleID (' . $RoleID . ') successfully.<br>';
            return true;
        } else {
            if($this->verbose_actions) echo 'UserID (' . $UserID . ') already assigned to RoleID (' . $RoleID . ').' . '<br>';
            return true;
        }
    }

    public function unassign($RoleID, $UserID)
    {
        global $mysqli;

        $affected_rows = $mysqli->prepared_query("DELETE FROM UARPC__userroles WHERE UserID=? AND RoleID=?", 'ii', [$UserID,$RoleID]);
        if (!$affected_rows) {
            if($this->verbose_actions) echo 'Error: role/unassign(' . $RoleID . ', ' . $UserID . ') did not report any databasechange' . '<br>';
        } else {
            if($this->verbose_actions) echo 'Unnasigned role(' . $RoleID . '), from user(' . $UserID . ')<br>';
            return $affected_rows;
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
            if($this->verbose_actions) echo 'RoleID for ' . $title . ' does not exist' . '<br>';
            return false;
        } else {
            if($this->verbose_actions) echo 'RoleId returned is ' . $res[0]['RoleID'] . '<br>';
            return $res[0]['RoleID'];
        }
    }



    public function list($UserID=null)
    {
        global $mysqli;

        //$res = $mysqli->query("SELECT `RoleID`, `title`, `description` from UARPC__roles");

        $sql = "SELECT `r`.`RoleID`, `r`.`title`, `r`.`description`, `ur`.`UserID` as thisUserAssigned 
                FROM `uarpc__roles` `r` 
                LEFT JOIN `uarpc__userroles` ur ON (`r`.`RoleID` = `ur`.`RoleID` AND `ur`.`UserID` = " . (int) $this->UserID .  ")";
        $res = $mysqli->query($sql);

        if( $res->num_rows ){
            $roles = [];
            while( $row = $res->fetch_assoc() ){
                if( is_null($row['thisUserAssigned']) )
                    $row['assigned'] = false;
                    else
                    $row['assigned'] = true;
                unset($row['thisUserAssigned']);
                $roles[ $row['RoleID'] ] = $row;
            }
            return $roles;
        } else {
            return [];
        }
   
    }

    /**
     * List users belonging to a role
     *
     * @param int $RoleID ID
     *
     * @return void
     */
    public function listUsers($RoleID)
    {
        global $mysqli;

        $sql = "SELECT * FROM `uarpc__userroles` `ur` 
                JOIN `uarpc__roles` `r` ON `r`.`RoleID` = `ur`.`RoleID` 
                WHERE `ur`.`RoleID` = ?";
        $res = $mysqli->prepared_query($sql, 'i', [$RoleID]);

        $users = [];
        if (count($res)) {
            foreach($res as $row){
                $users[$row['UserID']] = [
                                            'UserID' => $row['UserID']
                                         ];
            }
        }
        return $users;
    }

    /**
     * Return role title
     *
     * @param int $RoleID
     *
     * @return string Role title
     */
    public function getTitle($RoleID)
    {
        global $mysqli;
        $res = $mysqli->prepared_query("SELECT `title` from UARPC__roles WHERE RoleID=?", 'i', [$RoleID]);
        if (!count($res)) {
            if($this->verbose_actions) echo 'Role(' . $RoleID . ') does not exist' . '<br>';
            return false;
        } else {
            if($this->verbose_actions) echo 'Role title returned is ' . $res[0]['title'] . '<br>';
            return $res[0]['title'];
        }
    }

    /**
     * Return role description
     *
     * @param int $RoleID
     *
     * @return string Role description
     */
    public function getDescription($RoleID)
    {
        global $mysqli;
        $res = $mysqli->prepared_query("SELECT `description` from UARPC__roles WHERE RoleID=?", 'i', [$RoleID]);
        if (!count($res)) {
            if($this->verbose_actions) echo 'Role(' . $RoleID . ') does not exist' . '<br>';
            return false;
        } else {
            if($this->verbose_actions) echo 'Role description returned is ' . $res[0]['description'] . '<br>';
            return $res[0]['description'];
        }
    }




}
