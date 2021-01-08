<style>

</style>

# PERMISSIONS OBJECT FUNCTIONS

The permissions object has several functions for administering the permissions.

    $uarpc->permissions->add($title,? $desc,? int $enabled) : PermID
    $uarpc->permissions->assign( int $PermID, $RoldId) : bool
    $uarpc->permissions->unassign( int $PermID, $RoldId) : bool
    $uarpc->permissions->getTitle( int $PermID ) : string
    $uarpc->permissions->getDescription( int $PermID ) : string

    $uarpc->permissions->id($title) : PermID
    $uarpc->permissions->edit($PermID,$title,? $description,? $enabled) : bool
    $uarpc->permissions->list() : [PermID=>[obj]]

    $uarpc->permissions->state(PermID) : bool
    $uarpc->permissions->enable(PermID) : bool
    $uarpc->permissions->disable(PermID) : bool
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
    $uarpc->permissions->id( string $title ) : int

<hr>
<hr>

## <dd>**permissions->unassign**</dd>

Un-Assign a role to a permission, the function is available from the $uarpc object.

### **Description**

    $uarpc->permissions->unassign( int $permissionId, int $RoleID ) : boolean

### **Parameters**

_permissionId_
    The PermissionId for the permissions you want to unassign
_RoleID_
    $RoleID of role.

### **Return Values**

On success returns true else returns false.

### **Examples**

_Example #1 unassigning role admins to write permission_

    $permissionId = $uarpc->permissions->add('write_access');
    $roleId = $uarpc->roles->add('admins');
    $uarpc->permissions->unassign($permissionId, $roleId);

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

## <dd>**permissions->getTitle**</dd>

Return the name of a permission, the function is available from the $uarpc object.

### **Description**

    $uarpc->permissions->getTitle( int $PermissionID ) : string

### **Parameters**

_PermissionID_
    PermissionID you want to look up

### **Return Values**

Returns the name of the permission.

### **Examples**

_Example #1 return the PermissionId for the admins role_

    echo $uarpc->permissions->getTitle(2);
    // example outputs: /invoice/write_access

## <dd>**permissions->getDescription**</dd>

Return the description of a permission, the function is available from the $uarpc object.

### **Description**

    $uarpc->permissions->getDescription( int $PermissionID ) : string

### **Parameters**

_PermissionID_
    PermissionID you want to look up

### **Return Values**

Returns the name of the permission.

### **Examples**

_Example #1 return the description for PermissionID 2

    echo $uarpc->permissions->getDescription(2);
    // example outputs: User can edit and write invoices

