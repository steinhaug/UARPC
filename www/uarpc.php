<?php

require 'UARPC_PermissionManager.php';
require 'UARPC_RoleManager.php';
require 'UARPC_UserManager.php';
require 'UARPC_base.php';

/**
 * UARPC loader - Making sure above files are loaded and $mysqli is running
 * 
 * (c) Kim Steinhaug
 * http://steinhaug.no/
 * 
 * User Access, Roles, Permissions and Configurations framework Tools for administering user access.
 * Url: https://gitlab.com/steinhaug/uarpc
 * 
 */
class uarpc
{
    public function __construct()
    {
        global $mysqli;

        if( !method_exists($mysqli, 'prepared_insert') ){
            die('Fatal error, steinhaug/uarpc requires class.mysqli.php. Make sure $mysqli is setup and try again.');
        }
    }
}