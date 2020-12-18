<?php
require '../credentials.php';
require 'class.mysqli.php';
require 'class.uarpc.php';

define('USER_KIM', 999);
define('ROLE1','Manager');
define('ROLE2','Boss');
define('PERM1','/invoice/read');
define('PERM2','/invoice/write');

$uarpc = new UARPC_base;


/*
// Unassigned test
$roleId = $uarpc->roles->add(ROLE1);
$uarpc->roles->assign($roleId, USER_KIM);
$uarpc->roles->unassign($roleId, USER_KIM);
*/

$permissionId = $uarpc->permissions->add(PERM1, 'User can read invoice');
$roleId = $uarpc->roles->add(ROLE2);
$uarpc->permissions->assign($permissionId, $roleId);
$uarpc->permissions->unassign($permissionId, $roleId);

/*
echo '<hr>Functional approach:<br>';

$roleid =       $uarpc->addRole(ROLE1);
$permissionid = $uarpc->addPermission(PERM1, 'User can read invoice');
$uarpc->assignRole($roleid, USER_KIM);
$uarpc->assignPermission($permissionid, $roleid);

echo '<hr>Object oriented approach:<br>';

$roleid = $uarpc->roles->add(ROLE1);
$permissionid = $uarpc->permissions->add(PERM1, 'User can read invoice');
$uarpc->roles->assign($roleid, USER_KIM);
$uarpc->permissions->assign($permissionid, $roleid);

$roleid = $uarpc->roles->add(ROLE2);
$permissionid = $uarpc->permissions->add(PERM2, 'User can read invoice');
$uarpc->roles->assign($roleid, USER_KIM);
$uarpc->permissions->assign($permissionid, $roleid);

echo '<hr>';

$roleId = $uarpc->roles->id(ROLE1);
echo 'RoleId for ' . ROLE1 . ' is ' . $roleId . '<br>'; 

$roleId = $uarpc->roles->id(ROLE2);
echo 'RoleId for ' . ROLE2 . ' is ' . $roleId . '<br>'; 

$permissionId = $uarpc->permissions->id(PERM1);
echo 'PermissionId for ' . PERM1 . ' is ' . $permissionId . '<br>'; 

$permissionId = $uarpc->permissions->id(PERM2);
echo 'PermissionId for ' . PERM2 . ' is ' . $permissionId . '<br>'; 
*/

#$permissionId = $uarpc->permissions->id('/invoice/read');
#echo 'PermissionId for /invoice/read is ' . $permissionId . '<br>'; 
