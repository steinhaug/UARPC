# UARPC v1.0

User Access, Roles, Permissions and Configurations framework
Tools for administering user access.

https://lucid.app/lucidspark/invitations/accept/19b35103-eaa4-4b0c-82c4-c124a1ac8880


## Install by composer

To install the library use composer:

    composer require steinhaug/uarpc

    or specify version

    composer require "steinhaug/uarpc:^1.*"

Make sure that project is running **class.mysqli.php** as this is required for UARPC to work. To initialize, use like this:

    new uarpc; // loading and $mysqli check
    $uarpc = new UARPC_base($UserId);
    ... see docs for rest


## Functions

```LESS

    $userId = 999; // UserID from main system

    $uarpc->roles->add($title, $desc);
    $uarpc->roles->assign($roleId, $userId);

    $uarpc->permissions->add($title, $desc);
    $uarpc->permissions->assign($permissionId, $roleId);

    $roleId = $uarpc->roles->id('ROLE_STRING_NAME');
    $permissionId = $uarpc->permissions->id('ROLE_STRING_NAME');

```


## delete all below, temporary data




uarpc__useroverridepermissions


roles->add
roles->assign
roles->unassign
roles->id
permission->add
permission->assign
permission->unassign
permission->id

user->has_access / allowed
user->check

roles->hasPermission


user->enforce a permission on a user
Returns true if the user has the permission.
If the user does not have the permission two things happen:
A 403 HTTP status code header will be sent to the web client.
Script execution will terminate with a 'Forbidden: You do not have permission to access this resource.' message.

->count of entitys
->edit
->getDescription
->getTitle
->titleId
->resetAssignments


#
SELECT `TP`.RoleID, `TP`.Title, `TP`.Description FROM uarpc__roles AS `TP`
LEFT JOIN uarpc__rolepermissions AS `TR` ON (`TR`.RoleID=`TP`.RoleID)
WHERE PermissionID=1

# get all roles for a user
SELECT * FROM uarpc__userroles uus 
JOIN uarpc__roles uro ON ( uro.RoleID = uus.RoleID ) 
WHERE uus.UserID = 999


SELECT * FROM 
FROM uarpc__permissions up 
JOIN uarpc__rolepermissions urp ON ( urp.PermissionID = up.PermissionID ) 
JOIN uarpc__roles ur ON ( ur.RoleID = urp.RoleID ) 
WHERE ur.UserID = 999


'/invoice/read'


# role:7, list all permissions
SELECT * 
FROM uarpc__permissions up 
JOIN uarpc__rolepermissions urp ON ( urp.PermissionID = up.PermissionID ) 
JOIN uarpc__roles ur ON ( ur.RoleID = urp.RoleID ) 
WHERE ur.RoleID = 7




