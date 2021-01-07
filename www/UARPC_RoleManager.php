<?php


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
            echo 'Role (' . $RoleID . ') was successfully updated.<br>';
            return true;
        } else {
            echo 'Role (' . $RoleID . ') was NOT updated.<br>';
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
            echo 'UserID (' . $UserID . ') assigned to RoleID (' . $RoleID . ') successfully.<br>';
            return true;
        } else {
            echo 'UserID (' . $UserID . ') already assigned to RoleID (' . $RoleID . ').' . '<br>';
            return true;
        }
    }

    public function unassign($RoleID, $UserID)
    {
        global $mysqli;

        $affected_rows = $mysqli->prepared_query("DELETE FROM UARPC__userroles WHERE UserID=? AND RoleID=?", 'ii', [$UserID,$RoleID]);
        if (!$affected_rows) {
            echo 'Error: role/unassign(' . $RoleID . ', ' . $UserID . ') did not report any databasechange' . '<br>';
        } else {
            echo 'Unnasigned role(' . $RoleID . '), from user(' . $UserID . ')<br>';
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
            echo 'RoleID for ' . $title . ' does not exist' . '<br>';
            return false;
        } else {
            echo 'RoleId returned is ' . $res[0]['RoleID'] . '<br>';
            return $res[0]['RoleID'];
        }
    }



    public function list($UserID=null)
    {
        global $mysqli;

        $res = $mysqli->query("SELECT `RoleID`, `title`, `description` from UARPC__roles");
        if( $res->num_rows ){
            $roles = [];
            while( $row = $res->fetch_assoc() ){
                $roles[ $row['RoleID'] ] = $row;
            }
            return $roles;
        } else {
            return [];
        }
   
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
            echo 'Role(' . $RoleID . ') does not exist' . '<br>';
            return false;
        } else {
            echo 'Role title returned is ' . $res[0]['title'] . '<br>';
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
            echo 'Role(' . $RoleID . ') does not exist' . '<br>';
            return false;
        } else {
            echo 'Role description returned is ' . $res[0]['description'] . '<br>';
            return $res[0]['description'];
        }
    }




}
