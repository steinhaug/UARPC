# UARPC

User Access, Roles, Permissions and Configurations framework
Tools for administering user access.

https://lucid.app/lucidspark/invitations/accept/19b35103-eaa4-4b0c-82c4-c124a1ac8880


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
<style>
dd { margin-left:0;padding-left:0.5em;background-color: #818; color: #fff;}
</style>

# ROLES OBJECT FUNCTIONS

The roles object has several functions for administering the roles.

## <dd>**roles->add**</dd>

Add a role to the system, the function is available from the $uarpc object.
### **Description**

    $uarpc->roles->add( string $title, string $description ) : int
### **Parameters**

_title_
    Unique name of role
_description_
    Descripption of the role, used in admin explaining the role

### **Return Values**

On success returns the RoleID, else it will return 0.

### **Examples**

_Example #1 adding 2 new roles_

    $uarpc->roles->add('/invoice/read','');
    $uarpc->roles->add('/invoice/write','');


<hr>
<hr>

## <dd>**roles->assign**</dd>

Assign a user to a role, the function is available from the $uarpc object.
### **Description**

    $uarpc->roles->assign( int $RoleID, int $UserID ) : boolean
### **Parameters**

_RoleID_
    The RoleID for the Role you want
_UserID_
    UserID from your own application logic, outside UARPC

### **Return Values**

On success returns true else returns false.

### **Examples**

_Example #1 assigning user 490 to Role admins_

    $roleid = $uarpc->roles->add('admins');
    $uarpc->roles->assign($roleid, 490);

<hr>
<hr>

## <dd>**roles->id**</dd>

Return the RoleId of a role, the function is available from the $uarpc object.
### **Description**

    $uarpc->roles->id( string $title ) : int
### **Parameters**

_title_
    Role name you want to look up

### **Return Values**

On success will return the RoleId, false if fail.
### **Examples**

_Example #1 return the RoleId for the admins role_

    $roleId = $uarpc->roles->add('admins');
    // Lets say the roleId from previous line was 8

    $roleId = $uarpc->roles->id('admins');
    // $roleId = 8

