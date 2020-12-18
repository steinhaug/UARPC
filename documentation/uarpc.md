# UARPC - User Access Roles Permission Configuration

Framework for role and permission management for a user. The aim for the framework is to deliver all tools and operations required for daily usage with many users. There is also special attention added in regards of how to edit and manage the roles and permissions, so that a GUI can be plugged on top later.

Aim for the framework is to be OOP.

## <dd>**UARPC_base;**</dd>

Instantiates the class with required sub classes.

### **Description**

    $uarpc = new UARPC_base;

## Documentation for sub classes


[\$uarpc->roles](roles.md),
[\$uarpc->permissions](permissions.md)

    // Roles class
    $uarpc->roles

    // Permissions class
    $uarpc->permissions

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

