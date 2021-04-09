# UARPC - User Access Roles Permission Configuration

Framework for role and permission management for a user. The aim for the framework is to deliver all tools and operations required for daily usage with many users. There is also special attention added in regards of how to edit and manage the roles and permissions, so that a GUI can be plugged on top later.

Aim for the framework is to be OOP.

## <dd>**AUTOLOADING with Composer**</dd>

For the autoloader to work correctly you will need to add a line in your configuration.

    new uarpc;

This will make sure all required libraries gets loaded, also it gives you some tools like creating needed tables in a fresh install, or letting you rename tables with a new prefix. The autoload initializer has the following options:

    // reserved values for the __constructor are : "setup", null
    new uarpc("setup");     // will install all the database tables.

    // any other value will be used as db_prefix
    new uarpc("myPrefix_"); // sets the $db_prefix for the UARPC instance.

So to initiate the instance from autoload for user with userid 99 it should read:

    new uarpc;
    $uarpc = new UARPC_base(99);

If you do not want to use the autoloader you could manually include the following files, remember to change the PATH part below with your setups path:

    require PATH . UARPC_UserManager.php
    require PATH . UARPC_RoleManager.php
    require PATH . UARPC_PermissionManager.php
    require PATH . UARPC_base.php
    $uarpc = new UARPC_base(99);

## <dd>**UARPC_base;**</dd>

Instantiates the class with required sub classes.

### **Description**

    $uarpc = new UARPC_base;
    or
    $uarpc = new UARPC_BASE($UserID);

    $uarpc->havePermission( string $PermTitle, ? int $UserID ) : boolean
    $uarpc->permEnabled( string $PermTitle ) : boolean

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


## <dd>**permEnabled()**</dd>

Check if the permission is enabled from a system perspective.

### **Description**

    $uarpc->permEnabled( string $PermissionTitle ) : boolean

### **Parameters**

_PermissionTitle_  
Name of permission to check.  

### **Return Values**

If enabled returns true else returns false.

### **Examples**

_Example #1 check if /invoice/write is enabled_

    $uarpc = new UARPC_BASE(999);
    if( $uarpc->permEnabled('/invoice/write') ){
        // Permission is enabled
    }

<hr>
<hr>

## Documentation for sub classes


roles class: [\$uarpc->roles](UARPC.Roles.md)  
permissions class: [\$uarpc->permissions](UARPC.Permissions.md)  
users class: [\$uarpc->users](UARPC.Users.md)  

    // Roles class function paths
    $uarpc->roles->

    // Permissions class function paths
    $uarpc->permissions->

    // Users class function paths
    $uarpc->users->

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
