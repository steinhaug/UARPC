<?php

/**
 * UARPC - Role Manager v1.6.0
 *
 * All role based functions and operations goes into this class
 */
class UARPC_RoleManager
{
    // default UserID
    public $UserID = null;

    // Database prefix
    public $db_prefix = 'uarpc_';

    // if true will output lots of debugging info
    public $verbose_actions = false;

    // method name to process the final returned data from list functions
    public $returnMethod_formatter = null;
    public $returnMethod_param1 = null; // possible parameter for formatter

    public function __construct($UserID = null, $verbose_actions = false, $db_prefix = null)
    {
        $this->verbose_actions = $verbose_actions;

        if (isset($GLOBALS['steinhaugUarpcDbPrefix'])) {
            $this->db_prefix = $GLOBALS['steinhaugUarpcDbPrefix'];
        }

        if ($db_prefix !== null) {
            $this->db_prefix = $db_prefix;
        }

        if ($this->verbose_actions) {
            echo 'UARPC_RoleManager init: ' . time() . '<br>';
        }

        if ($UserID !== null) {
            $this->UserID = $UserID;
            if ($this->verbose_actions) {
                echo 'UserID set for ' . $this->UserID . '<br>';
            }
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
    public function add($title, $description = '')
    {
        global $mysqli;
        $res = $mysqli->prepared_query("SELECT `RoleID` FROM `" . $this->db_prefix . "_roles` WHERE `title`=?", 's', [$title]);
        if (!count($res)) {
            $sql = [
                "INSERT INTO `" . $this->db_prefix . "_roles` (`title`,`description`) VALUES (?,?)",
                "ss",
                [$title, $description]
            ];
            $result = $mysqli->prepared_insert($sql);
            if ($this->verbose_actions) {
                echo 'Role created successfully, RoleID: ' . $result . '<br>';
            }
            return $result;
        } else {
            if ($this->verbose_actions) {
                echo 'Role (' . $res[0]['RoleID'] . ') already exists.<br>';
            }
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
    public function delete($RoleID)
    {
        global $mysqli;

        if ($mysqli->prepared_query1("SELECT count(*) as `count` FROM `" . $this->db_prefix . "_rolepermissions` WHERE `RoleID`=?", 'i', [$RoleID], 0)) {
            if ($this->verbose_actions) {
                echo 'Delete role (' . $RoleID . ') error, connected to permissions.<br>';
            }
            return false;
        }

        $res = $mysqli->prepared_query("DELETE FROM `" . $this->db_prefix . "_roles` WHERE `RoleID`=?", 'i', [$RoleID]);
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
    public function edit($RoleID, $title, $description = '')
    {
        global $mysqli;

        $sql = [
            "UPDATE `" . $this->db_prefix . "_roles` SET `title`=?, `description`=? WHERE `RoleID`=?",
            "ssi",
            [$title, $description, $RoleID]
        ];
        $affected_rows = $mysqli->prepared_insert($sql);
        if ($affected_rows) {
            if ($this->verbose_actions) {
                echo 'Role (' . $RoleID . ') was successfully updated.<br>';
            }
            return true;
        } else {
            if ($this->verbose_actions) {
                echo 'Role (' . $RoleID . ') was NOT updated.<br>';
            }
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

        $res = $mysqli->prepared_query("SELECT `UserID`,`RoleID` FROM `" . $this->db_prefix . "_userroles` WHERE `UserID`=? AND `RoleID`=?", 'ii', [$UserID, $RoleID]);
        if (!count($res)) {
            $sql = [
                "INSERT INTO " . $this->db_prefix . "_userroles (`UserID`,`RoleID`,`AssignmentDate`) VALUES (?,?,?)",
                "iii",
                [$UserID, $RoleID, time()]
            ];
            $result = $mysqli->prepared_insert($sql);
            if ($this->verbose_actions) {
                echo 'UserID (' . $UserID . ') assigned to RoleID (' . $RoleID . ') successfully.<br>';
            }
            return true;
        } else {
            if ($this->verbose_actions) {
                echo 'UserID (' . $UserID . ') already assigned to RoleID (' . $RoleID . ').' . '<br>';
            }
            return true;
        }
    }

    public function unassign($RoleID, $UserID)
    {
        global $mysqli;

        $affected_rows = $mysqli->prepared_query("DELETE FROM `" . $this->db_prefix . "_userroles` WHERE `UserID`=? AND `RoleID`=?", 'ii', [$UserID, $RoleID]);
        if (!$affected_rows) {
            if ($this->verbose_actions) {
                echo 'Error: role/unassign(' . $RoleID . ', ' . $UserID . ') did not report any databasechange' . '<br>';
            }
        } else {
            if ($this->verbose_actions) {
                echo 'Unnasigned role(' . $RoleID . '), from user(' . $UserID . ')<br>';
            }
            return $affected_rows;
        }
    }

    /**
     * Check if a user is assigned to a role
     *
     * @param [type] $RoleID RoleID to check
     * @param [type] $UserID UserID to check
     *
     * @return bool True if user is assigned and false if user is not.
     */
    public function isAssigned($RoleID, $UserID)
    {
        global $mysqli;

        $res = $mysqli->prepared_query("SELECT * FROM `" . $this->db_prefix . "_userroles` WHERE `UserID`=? and `RoleID`=?", 'ii', [$UserID, $RoleID]);
        if (count($res)) {
            if ($this->verbose_actions) {
                echo 'isAssigned, user ' . $UserID . ' IS assigned for role ' . $RoleID;
            }
            return true;
        } else {
            if ($this->verbose_actions) {
                echo 'isAssigned, user ' . $UserID . ' IS NOT assigned for role ' . $RoleID;
            }
            return false;
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

        $res = $mysqli->prepared_query("SELECT `RoleID` FROM `" . $this->db_prefix . "_roles` WHERE `title`=?", 's', [$title]);
        if (!count($res)) {
            if ($this->verbose_actions) {
                echo 'RoleID for ' . $title . ' does not exist' . '<br>';
            }
            return false;
        } else {
            if ($this->verbose_actions) {
                echo 'RoleId returned is ' . $res[0]['RoleID'] . '<br>';
            }
            return $res[0]['RoleID'];
        }
    }



    /**
     * List all available roles, + current users assignment
     *
     * @param int $UserID UserID for user to check for assignment
     *
     * @return array Array of roles
     */
    public function list($UserID = null)
    {
        global $mysqli;

        if ($UserID === null) {
            $UserID = $this->UserID;
        }

        //$res = $mysqli->query("SELECT `RoleID`, `title`, `description` from uarpc__roles");

        $sql = "SELECT `r`.`RoleID`, `r`.`title`, `r`.`description`, `ur`.`UserID` AS `thisUserAssigned` 
                FROM `" . $this->db_prefix . "_roles` `r` 
                LEFT JOIN `" . $this->db_prefix . "_userroles` ur ON (`r`.`RoleID` = `ur`.`RoleID` AND `ur`.`UserID` = " . (int) $UserID . ")";
        $res = $mysqli->query($sql);

        if ($res->num_rows) {
            $roles = [];
            while ($row = $res->fetch_assoc()) {
                if (is_null($row['thisUserAssigned'])) {
                    $row['assigned'] = false;
                } else {
                    $row['assigned'] = true;
                }
                $roles[ $row['RoleID'] ] = $row;
            }
            if ($this->returnMethod_formatter !== null) {
                return $this->{$this->returnMethod_formatter}($roles, __FUNCTION__);
            } else {
                return $roles;
            }
        } else {
            if ($this->returnMethod_formatter !== null) {
                return $this->{$this->returnMethod_formatter}([], __FUNCTION__);
            } else {
                return [];
            }
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

        $sql = "SELECT * FROM `" . $this->db_prefix . "_userroles` `ur` 
                JOIN `" . $this->db_prefix . "_roles` `r` ON `r`.`RoleID` = `ur`.`RoleID` 
                WHERE `ur`.`RoleID` = ?";
        $res = $mysqli->prepared_query($sql, 'i', [$RoleID]);

        $users = [];
        if (count($res)) {
            foreach ($res as $row) {
                $users[$row['UserID']] = [
                                            'UserID' => $row['UserID']
                                         ];
            }
        }

        if ($this->returnMethod_formatter !== null) {
            return $this->{$this->returnMethod_formatter}($users, __FUNCTION__);
        } else {
            return $users;
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
        $res = $mysqli->prepared_query("SELECT `title` from `" . $this->db_prefix . "_roles` WHERE `RoleID`=?", 'i', [$RoleID]);
        if (!count($res)) {
            if ($this->verbose_actions) {
                echo 'Role(' . $RoleID . ') does not exist' . '<br>';
            }
            return false;
        } else {
            if ($this->verbose_actions) {
                echo 'Role title returned is ' . $res[0]['title'] . '<br>';
            }
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
        $res = $mysqli->prepared_query("SELECT `description` from `" . $this->db_prefix . "_roles` WHERE `RoleID`=?", 'i', [$RoleID]);
        if (!count($res)) {
            if ($this->verbose_actions) {
                echo 'Role(' . $RoleID . ') does not exist' . '<br>';
            }
            return false;
        } else {
            if ($this->verbose_actions) {
                echo 'Role description returned is ' . $res[0]['description'] . '<br>';
            }
            return $res[0]['description'];
        }
    }

    /**
     * Chainable formatter setting
     *
     * @param string $format Name of format to be recieved
     *
     * @return void
     */
    public function format($format, $param1 = null)
    {
        $valid_formatters = ['option'];
        if (!in_array($format, $valid_formatters)) {
            die('<h1>$UARPC Role error - Must be fixed!</h1><div>-&gt;format(formatter) error: &quot;' . $format . '&quot;.<br>Possible formatters are: &quot;' . implode('&quot;, &quot;', $valid_formatters) . '&quot;</div>');
        }

        if ($format == 'option') {
            $this->returnMethod_formatter = 'format__option';
        }

        if ($param1 !== null) {
            $this->returnMethod_param1 = $param1;
        }

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
    public function format__option($data, $caller_method)
    {
        $p1 = $this->returnMethod_param1;

        $this->returnMethod_formatter = null;
        $this->returnMethod_param1 = null;
        if ($caller_method == 'list') {
            $markup = '';
            foreach ($data as $key => $val) {
                if ($p1 !== null and $p1 == $key) {
                    $markup .= '<option value="' . $key . '" selected="true">' . $val['title'] . '</option>' . "\n";
                } else {
                    $markup .= '<option value="' . $key . '">' . $val['title'] . '</option>' . "\n";
                }
            }
            return $markup;
        } elseif ($caller_method == 'listUsers') {
            $markup = '';
            foreach ($data as $key => $val) {
                if ($p1 !== null and $p1 == $key) {
                    $markup .= '<option value="' . $key . '" selected="true">UserID ' . $val['UserID'] . '</option>' . "\n";
                } else {
                    $markup .= '<option value="' . $key . '">UserID ' . $val['UserID'] . '</option>' . "\n";
                }
            }
            return $markup;
        }

        return 'Formatter error, unknown caller: ' . $ref;
    }
}
