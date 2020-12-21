# UARPC - User Access Roles Permission Configuration

Framework for role and permission management for a user. The aim for the framework is to deliver all tools and operations required for daily usage with many users. There is also special attention added in regards of how to edit and manage the roles and permissions, so that a GUI can be plugged on top later.

Aim for the framework is to be OOP.

## <dd>**UARPC_base;**</dd>

Instantiates the class with required sub classes.

### **Description**

    $uarpc = new UARPC_base;
    or
    $uarpc = new UARPC_BASE($UserID);

## <dd>**havePermission()**</dd>

Check if a user have permission. Logic first checks if the user is denied a permission, then if the permission is specifically allowed for the user and finally if the permission is allowed by the role(s) assigned to the user.
### **Description**

    $uarpc->havePermission( string $PermissionTitle, int $UserID=null ) : boolean

### **Parameters**

_PermissionTitle_  
Name of permission to check.  
_UserID_  
Userid to perform check against. Optional, or uses class initiated value.

### **Return Values**

On success returns true else returns false.

### **Examples**

_Example #1 check if user 999 are allowed /invoice/write_

    $uarpc = new UARPC_BASE(999);
    if( $uarpc->havePermission('/invoice/write') ){
        // User are permitted
    }

<hr>
<hr>

## Documentation for sub classes


[\$uarpc->roles](roles.md),
[\$uarpc->permissions](permissions.md)
[\$uarpc->users](users.md)

    // Roles class
    $uarpc->roles

    // Permissions class
    $uarpc->permissions

    // Users class
    $uarpc->users

## Example

```LESS
define('USER_KIM', 999);
define('ROLE1','Manager');
define('ROLE2','Boss');
define('PERM1','/invoice/read');
define('PERM2','/invoice/write');

$uarpc = new UARPC_base;

$roleid = $uarpc->roles->add(ROLE1);
$permissionid = $uarpc->permissions->add(PERM1, 'User can read invoice');
$uarpc->roles->assign($roleid, USER_KIM);
$uarpc->permissions->assign($permissionid, $roleid);

$roleid = $uarpc->roles->add(ROLE2);
$permissionid = $uarpc->permissions->add(PERM2, 'User can read invoice');
$uarpc->roles->assign($roleid, USER_KIM);
$uarpc->permissions->assign($permissionid, $roleid);
```
