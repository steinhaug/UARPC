<style>
dd { margin-left:0;padding-left:0.5em;background-color: #818; color: #fff;}
</style>

# PERMISSIONS OBJECT FUNCTIONS

The permissions object has several functions for administering the permissions.

## <dd>**permissions->add**</dd>

Add a permission to the system, the function is available from the $uarpc object.

### **Description**

    $uarpc->permissions->add( string $title, string $description ) : int

### **Parameters**

_title_
    Unique name of permission
_description_
    Descripption of the permission, used in admin explaining the permission

### **Return Values**

On success returns the PermissionId, else it will return 0.

### **Examples**

_Example #1 adding 2 new permissions

    $uarpc->permissions->add('/invoice/read','');
    $uarpc->permissions->add('/invoice/write','');


<hr>
<hr>

## <dd>**permissions->assign**</dd>

Assign a role to a permission, the function is available from the $uarpc object.

### **Description**

    $uarpc->permissions->assign( int $permissionId, int $RoleID ) : boolean

### **Parameters**

_permissionId_
    The PermissionId for the permissions you want
_RoleID_
    $RoleID of role.

### **Return Values**

On success returns true else returns false.

### **Examples**

_Example #1 assigning role admins to write permission_

    $permissionId = $uarpc->permissions->add('write_access');
    $roleId = $uarpc->roles->add('admins');
    $uarpc->permissions->assign($permissionId, $roleId);

<hr>
<hr>

## <dd>**permissions->id**</dd>

Return the PermissionId of a permission, the function is available from the $uarpc object.

### **Description**

    $uarpc->permissions->id( string $title ) : int

### **Parameters**

_title_
    Name of permission you want to look up

### **Return Values**

On success will return the PermissionId, false if fail.'

### **Examples**

_Example #1 return the PermissionId for the admins role_

    $permissionId = $uarpc->permissions->add('write_access');
    // Lets say the PermissionId from previous line was 8

    $permissionId = $uarpc->permissions->id('write_access');
    // $permissionId = 8

