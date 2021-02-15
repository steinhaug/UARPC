<?php

/**
 * UARPC - Permission Manager v1.3.0
 * 
 * All permission based operations and functions goes into this class.
 */
class UARPC_PermissionManager 
{

    // default UserID
    var $UserID = null;

    public $verbose_actions = false;

    public function __construct($UserID = null, $verbose_actions = false)
    {
        $this->verbose_actions = $verbose_actions;

        if($this->verbose_actions) echo 'UARPC_PermissionManager init: ' . time() . '<br>';

        if( $UserID !== null ){
            $this->UserID = $UserID;
            if($this->verbose_actions) echo 'UserID set for ' . $this->UserID . '<br>';
        }

    }

    /**
     * Add permission by name and description
     *
     * @param string $title
     * @param string $description
     * @param int $parentId PermissionID for parent node, or null. Strictly for presentational purposes
     * @param int $enabled Boolval for stating if a permission is enabled or valid from a system perspective
     *
     * @return int Returns PermissionId on success
     */
    public function add($title, $description='', $parentId = null, $enabled = 1)
    {
        global $mysqli;

        $res = $mysqli->prepared_query("SELECT PermissionID from UARPC__permissions WHERE title=?", 's', [$title]);
        if( !count($res) ){
            if ($parentId === null) {
                $sql = [
                    "INSERT INTO UARPC__permissions (`parentid`,`enabled`,`title`,`description`) VALUES (null,?,?,?)",
                    "iss",
                    [boolval($enabled)?1:0,$title,$description]
                ];
            } else {
                $sql = [
                    "INSERT INTO UARPC__permissions (`parentid`,`enabled`,`title`,`description`) VALUES (?,?,?,?)",
                    "iiss",
                    [$parentId,boolval($enabled)?1:0,$title,$description]
                ];
            }
            $result = $mysqli->prepared_insert($sql);
            if($this->verbose_actions) echo 'Permission created successfully, PermissionID: ' . $result . '<br>';
            return $result;
        } else {
            if($this->verbose_actions) echo 'Permission (' . $res[0]['PermissionID'] . ') already exists.<br>';
            return $res[0]['PermissionID'];
        }

    }

    /**
     * Delete a permission from the DB
     *
     * @param int $PermissionID PermissionID to delete
     *
     * @return boolean True on success, false on failure
     */
    public function delete($PermissionID)
    {
        global $mysqli;

        if ($mysqli->prepared_query1("SELECT count(*) as `count` FROM `uarpc__rolepermissions` WHERE `PermissionID`=?", 'i', [$PermissionID], 0)){
            if($this->verbose_actions) echo 'Delete permission (' . $PermissionID . ') error, connected to roles.<br>';
            return false;
        }
        if( $mysqli->prepared_query1("SELECT count(*) FROM `uarpc__userallowpermissions` WHERE `PermissionID`=?", 'i', [$PermissionID], 0) ){
            if($this->verbose_actions) echo 'Delete permission (' . $PermissionID . ') error, connected to user override.<br>';
            return false;
        }
        if( $mysqli->prepared_query1("SELECT count(*) FROM `uarpc__userdenypermissions` WHERE `PermissionID`=?", 'i', [$PermissionID], 0) ){
            if($this->verbose_actions) echo 'Delete permission (' . $PermissionID . ') error, connected to user deny.<br>';
            return false;
        }

        $res = $mysqli->prepared_query("DELETE FROM UARPC__permissions WHERE PermissionID=?", 'i', [$PermissionID]);
        return $res;
    }

    /**
     * Update a permission
     *
     * @param int $PermissionID PermissionID to update
     * @param string $title Permission title
     * @param string $description Optional, permission description
     * @param int $parentId Optional, PermissionID for parent node, or null. Strictly for presentational purposes
     * @param bool $enabled Optional, True or false for enabled or not, null indicates no change
     *
     * @return void
     */
    public function edit($PermissionID, $title, $description='', $parentId = null, $enabled = null)
    {
        global $mysqli;

        if( $enabled !== null ){
            if( $parentId === null ){
                $sql = [
                    "UPDATE UARPC__permissions SET `parentId`=null, `enabled`=?, `title`=?, `description`=? WHERE PermissionID=?",
                    "issi",
                    [boolval($enabled)?1:0,$title,$description,$PermissionID]
                ];
            } else {
                $sql = [
                    "UPDATE UARPC__permissions SET `parentId`=?, `enabled`=?, `title`=?, `description`=? WHERE PermissionID=?",
                    "iissi",
                    [$parentId,boolval($enabled)?1:0,$title,$description,$PermissionID]
                ];
            }
        } else {
            if ($parentId === null) {
                $sql = [
                    "UPDATE UARPC__permissions SET `parentId`=null, `title`=?, `description`=? WHERE PermissionID=?",
                    "ssi",
                    [$title,$description,$PermissionID]
                ];
            } else {
                $sql = [
                    "UPDATE UARPC__permissions SET `parentId`=?, `title`=?, `description`=? WHERE PermissionID=?",
                    "issi",
                    [$parentId,$title,$description,$PermissionID]
                ];
            }
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
    * MySQL references: https://www.codeproject.com/Articles/818694/SQL-Queries-to-Manage-Hierarchical-or-Parent-child
    * 
    * @param mixed $RoleID Either the RoleID to check for connections, or a configuration object
    *
    * @return array Returns all of the enabled $PermissionsIDs  
    */
    public function list($RoleID = null)
    {
        global $mysqli;

        $__listType = 'default';
        $__orderby = '';
        $__conf = [
            'onlyEnabled' => false
        ];

        if( is_array($RoleID) ){

            if (isset($RoleID['list']) and in_array($RoleID['list'], ['default','parent'])) {
                $__listType = $RoleID['list'];
            }

            if( isset($RoleID['sort']) and in_array($RoleID['sort'], ['asc','desc']) ){

                if($__listType == 'parent'){
                    if( $RoleID['sort'] == 'asc' )
                        $__orderby = ' ORDER BY `title` ASC';
                    if( $RoleID['sort'] == 'desc' )
                        $__orderby = ' ORDER BY `title` DESC';
                } else {
                    if( $RoleID['sort'] == 'asc' )
                        $__orderby = ' ORDER BY `up`.`title` ASC';
                    if( $RoleID['sort'] == 'desc' )
                        $__orderby = ' ORDER BY `up`.`title` DESC';
                }
            }

            if( isset($RoleID['onlyEnabled']) and in_array($RoleID['onlyEnabled'], [true,'1','true','yes']) ){
                $__conf['onlyEnabled'] = true;
            }

            if (isset($RoleID['RoleID']) and (int) $RoleID['RoleID']) {
                $RoleID = (int) $RoleID['RoleID'];
            } else {
                $RoleID = null;
            }
        }

        if( $RoleID !== null ){
            if ($__listType == 'parent') {
                $sql = 'SELECT `up`.`PermissionID`, `up`.`parentId`, `up`.`enabled`, `up`.`description`, 
                        CONCAT( COALESCE(`parent`.`title`, \'\'), `up`.`title`) AS `title`, COALESCE(`parent`.`title`,\'\') AS paTitle, `up`.`title` AS elTitle
                        FROM `uarpc__permissions` `up` 
                        JOIN `uarpc__rolepermissions` `urp` ON ( `urp`.`PermissionID` = `up`.`PermissionID` ) 
                        JOIN `uarpc__roles` `ur` ON ( `ur`.`RoleID` = `urp`.`RoleID` ) 
                        LEFT JOIN `uarpc__permissions` AS `parent` ON `up`.`parentId` = `parent`.`PermissionID`
                        WHERE `ur`.`RoleID` = ?
                        ' . $__orderby;
            } else {
                $sql = 'SELECT `up`.`PermissionID`, `up`.`parentId`, `up`.`enabled`, `up`.`title`, `up`.`description` 
                        FROM `uarpc__permissions` `up` 
                        JOIN `uarpc__rolepermissions` `urp` ON ( `urp`.`PermissionID` = `up`.`PermissionID` ) 
                        JOIN `uarpc__roles` `ur` ON ( `ur`.`RoleID` = `urp`.`RoleID` ) 
                        WHERE `ur`.`RoleID` = ?
                        ' . $__orderby;
            }
            $res = $mysqli->prepared_query($sql, 'i', [$RoleID]);

            $items = [];
            if (count($res)) {
                foreach($res as $item){

                    if ($__conf['onlyEnabled'] and $item['enabled']) {
                        unset($item['enabled']);
                        $items[ $item['PermissionID'] ] = $item;
                    } else if($__conf['onlyEnabled'] and !$item['enabled']) {
                        // We ignore adding the permission
                    } else {
                        $items[ $item['PermissionID'] ] = $item;
                    }

                    $items[ $item['PermissionID'] ] = $item;
                }
            }
            return $items;

        } else {

            if ($__listType == 'parent') {
                $res = $mysqli->query("SELECT `up`.`PermissionID`, `up`.`parentId`, `up`.`enabled`, `up`.`description`, CONCAT( COALESCE(parent.title, ''), `up`.`title`) AS `title`, COALESCE(parent.title,'') AS paTitle, `up`.`title` AS elTitle FROM `UARPC__permissions` `up` LEFT JOIN `UARPC__permissions` AS `parent` ON `up`.`parentId` = `parent`.`PermissionID`" . $__orderby);
            } else {
                $res = $mysqli->query("SELECT `up`.`PermissionID`, `up`.`parentId`, `up`.`enabled`, `up`.`title`, `up`.`description` FROM `UARPC__permissions` `up`" . $__orderby);
            }
            if( $res->num_rows ){
                $items = [];
                while( $row = $res->fetch_assoc() ){

                    if ($__conf['onlyEnabled'] and $row['enabled']) {
                        unset($row['enabled']);
                        $items[ $row['PermissionID'] ] = $row;
                    } else if($__conf['onlyEnabled'] and !$row['enabled']) {
                        // We ignore adding the permission
                    } else {
                        $items[ $row['PermissionID'] ] = $row;
                    }
                }
                return $items;
            } else {
                return [];
            }
   
        }
    }


    /**
     * List permissions and include data for a particular user
     *
     * @param array $conf Configuration for the list
     *
     * @return array Returns all of the enabled $PermissionsIDs  
     */
    public function listUser($conf)
    {
        global $mysqli;

        $conf['onlyEnabled'] = true;
        $permissions = $this->list($conf);

        if( $conf['UserID'] === null ){
            $UserID = $this->UserID;
        } else {
            $UserID = $conf['UserID'];
        }

        // Check if permission has been allowed or denied on user level
        $_processedPermissions = [];
        foreach ($permissions as $permission) {
            $PermissionID = $permission['PermissionID'];
            $permission['override'] = 'auto';
            $res = $mysqli->prepared_query("SELECT * from uarpc__userdenypermissions WHERE UserID=? and PermissionID=?", 'ii', [$UserID,$PermissionID]);
            if (count($res)) {
                $permission['override'] = 'block';
            }
            $res = $mysqli->prepared_query("SELECT * from uarpc__userallowpermissions WHERE UserID=? and PermissionID=?", 'ii', [$UserID,$PermissionID]);
            if (count($res)) {
                $permission['override'] = 'grant';
            }
            $_processedPermissions[$PermissionID] = $permission;
        }

        return $_processedPermissions;
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
