<?php
require '../credentials.php';
require 'class.mysqli.php';
require 'class.uarpc.php';

define('USER_KIM', 999);

$ur = new UARPC;

echo '<hr>Functional approach:<br>';

$roleid =       $ur->addRole('Manager');
$permissionid = $ur->addPermission('/invoice/read','User can read invoice');
$ur->assignRole($roleid, USER_KIM);
$ur->assignPermission($permissionid, $roleid);

echo '<hr>Object oriented approach:<br>';

$roleid = $ur->roles->add('Manager');
$permissionid = $ur->permissions->add('/invoice/read','User can read invoice');
$ur->roles->assign($roleid, USER_KIM);
$ur->permissions->assign($permissionid, $roleid);
