<style>
body {background-color: #eee;color:#000;}
dd { margin-left:0;padding-left:0em;color: #818;}
h1, h2, h3 { font-weight:bold;color:#818;}
h2:before {content:">";position:absolute;left:-20px;}
h2,h3 {border-bottom: 1px dotted #000;margin-bottom:1em;padding-bottom:0.25em;}
pre { background-color:#f8f8f8 !important; border: 1px dotted #ccc;}
code { color: #000 !important; }
</style>

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

