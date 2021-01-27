# PERMISSIONS OBJECT METHODS

The permissions object has several methods:

[\$uarpc->permissions->add($title,? $desc,? int $enabled) : PermID](UARPC-Permissions#permissions-add)

[\$uarpc->permissions->delete(PermID) : bool](UARPC-Permissions#permissions-delete)

[\$uarpc->permissions->edit(PermID, $title, ? $desc, ? $enabled) : bool](UARPC-Permissions#permissions-edit)

[\$uarpc->permissions->state(PermID) : bool](UARPC-Permissions#permissions-state)

[\$uarpc->permissions->enable(PermID) : bool](UARPC-Permissions#permissions-enable)

[\$uarpc->permissions->disable(PermID) : bool](UARPC-Permissions#permissions-disable)

[\$uarpc->permissions->assign( int $PermID, $RoldId) : bool](UARPC-Permissions#permissions-assign)

[\$uarpc->permissions->unassign( int $PermID, $RoldId) : bool](UARPC-Permissions#permissions-unassign)
    
[\$uarpc->permissions->id($title) : PermID](UARPC-Permissions#permissions-id)

[\$uarpc->permissions->list() : [PermID=>[obj]]](UARPC-Permissions#permissions-list)
    
[\$uarpc->permissions->getTitle( int $PermID ) : string](UARPC-Permissions#permissions-getTitle)

[\$uarpc->permissions->getDescription( int $PermID ) : string](UARPC-Permissions#permissions-getDescription)

## <dd>**permissions->add**</dd>

Add a permission to the system, the method is available from the $uarpc object.

#### **Description**

    $uarpc->permissions->add( string $title, string $description, ? int $parentId ) : int

#### **Parameters**

_title_  
    Unique name of permission  
_description_  
    Descripption of the permission, used in admin explaining the permission  
_parentId_   
    Optional. The PermissionID for the parent node, strictly for presentational purposes when describing the permissions in admin for users who admninisters the UARPC object.  

#### **Return Values**

On success returns the PermissionId, else it will return 0.

#### **Examples**

_Example #1 adding 2 new permissions

    $uarpc->permissions->add('/invoice/read');
    $uarpc->permissions->add('/invoice/write','Write access for invoicing module');

_Example #2 adding permissison with all parameters

    $PermID = $uarpc->permissions->add('/invoice');
    $uarpc->permissions->add('/invoice-write','Write access for invoicing module', $PermID);

<hr>
<hr>

## <dd>**permissions->delete**</dd>

Permission to delete.

#### **Description**

    $uarpc->permissions->delete( int $PermissionID ) : boolean

#### **Parameters**

_permissionId_  
    The PermissionId for the permissions you want to edit  

#### **Return Values**

On success returns true else returns false.

#### **Examples**

_Example #1 Delete permission 'write_access'_

    $permissionId = $uarpc->permissions->add('write_access');
    $uarpc->permissions->delete($permissionId);

<hr>
<hr>

## <dd>**permissions->edit**</dd>

Set permission state to enabled / active.

#### **Description**

    $uarpc->permissions->enable( $PermID, $title, ? $desc, ? $parentID, ? $enabled ) : bool

#### **Parameters**

_PermID_  
    The PermissionId for the permissions you want to edit  
_title_  
    Unique name of permission  
_description_  
    Optional, descripption of the permission, used in admin explaining the permission  
_parentId_  
    Optional. The PermissionID for the parent node, strictly for presentational purposes when describing the permissions in admin for users who admninisters the UARPC object.  
_enabled_  
    Optional, Boolean if permission is enabled or not. Defaults to 1.  

#### **Return Values**

On success returns true else returns false.

#### **Examples**

_Example #1 Edit permission 'write_access'_

    $permissionId = $uarpc->permissions->add('write_access');
    $uarpc->permissions->edit($permissionId, '/write access allowed');

_Example #2 Edit permission, disable and connect to parentID

    $parentId = $uarpc->permissions->add('skills/');
    $permissionId = $uarpc->permissions->add('write_access');
    $uarpc->permissions->edit($permissionId, '/write access allowed', $parentId, false);


<hr>
<hr>

## <dd>**permissions->state**</dd>

Return the permission state.

#### **Description**

    $uarpc->permissions->state( int $PermissionID ) : boolean

#### **Parameters**

_PermissionID_  
    The PermissionId for the permissions you want to check state  

#### **Return Values**

Returns true if permission is valid and enabled, false if else or fail.

#### **Examples**

_Example #1 Check state for 'write_access'_

    $permissionId = $uarpc->permissions->add('write_access');
    if( $uarpc->permissions->state($permissionId) );
        echo 'Enabled';
        else
        echo 'Disabled';

<hr>
<hr>

## <dd>**permissions->enable**</dd>

Set permission state to enabled / active.

#### **Description**

    $uarpc->permissions->enable( int $PermissionID ) : boolean

#### **Parameters**

_permissionId_
    The PermissionId for the permissions you want to edit

#### **Return Values**

On success returns true else returns false.

#### **Examples**

_Example #1 enable permission 'write_access'_

    $permissionId = $uarpc->permissions->add('write_access');
    $uarpc->permissions->enable($permissionId);


<hr>
<hr>

## <dd>**permissions->disable**</dd>

Set permission state to disabled / not active

#### **Description**

    $uarpc->permissions->disable( int $PermissionID ) : boolean

#### **Parameters**

_permissionId_
    The PermissionId for the permissions you want to edit

#### **Return Values**

On success returns true else returns false.

#### **Examples**

_Example #1 disable permission 'write_access'_

    $permissionId = $uarpc->permissions->add('write_access');
    $uarpc->permissions->disable($permissionId);

<hr>
<hr>

## <dd>**permissions->assign**</dd>

Assign a role to a permission, the function is available from the $uarpc object.

#### **Description**

    $uarpc->permissions->assign( int $permissionId, int $RoleID ) : boolean

#### **Parameters**

_permissionId_  
    The PermissionId for the permissions you want  
_RoleID_  
    $RoleID of role.  

#### **Return Values**

On success returns true else returns false.

#### **Examples**

_Example #1 assigning role admins to write permission_

    $permissionId = $uarpc->permissions->add('write_access');
    $roleId = $uarpc->roles->add('admins');
    $uarpc->permissions->assign($permissionId, $roleId);
    $uarpc->permissions->id( string $title ) : int

<hr>
<hr>

## <dd>**permissions->unassign**</dd>

Un-Assign a role to a permission, the function is available from the $uarpc object.

#### **Description**

    $uarpc->permissions->unassign( int $permissionId, int $RoleID ) : boolean

#### **Parameters**

_permissionId_  
    The PermissionId for the permissions you want to unassign  
_RoleID_  
    $RoleID of role.  

#### **Return Values**

On success returns true else returns false.

#### **Examples**

_Example #1 unassigning role admins to write permission_

    $permissionId = $uarpc->permissions->add('write_access');
    $roleId = $uarpc->roles->add('admins');
    $uarpc->permissions->unassign($permissionId, $roleId);

<hr>
<hr>


## <dd>**permissions->id**</dd>

Return the PermissionId of a permission, the function is available from the $uarpc object.

#### **Description**

    $uarpc->permissions->id( string $title ) : int

#### **Parameters**

_title_  
    Name of permission you want to look up  

#### **Return Values**

On success will return the PermissionId, false if fail.'

#### **Examples**

_Example #1 return the PermissionId for the admins role_

    $permissionId = $uarpc->permissions->add('write_access');
    // Lets say the PermissionId from previous line was 8

    $permissionId = $uarpc->permissions->id('write_access');
    // $permissionId = 8

<hr>
<hr>

## <dd>**permissions->list**</dd>

List all permissions.

#### **Description**

    $uarpc->permissions->list( ? $conf ) : array

#### **Parameters**

_conf_  
    Optional. An array parameters, at the moment theese are the possible settings  

    $conf = [
        'RoleID' => 1, // int RoleID
        'sort' => 'asc', // desc
        'list' => 'parent' // default
    ]

#### **Return Values**

An associated array: PermissionID=> [PermissionID, title, description].

#### **Examples**

_Example #1 Listing all permission_

```LESS
// Example returns 2 permissions
$permissions = $uarpc->permissions->list();

var_dump($permissions)
array (size=2)
  4 => 
    array (size=5)
      'PermissionID' => string '4' (length=1)
      'parentId' => null
      'enabled' => string '1' (length=1)
      'title' => string '/order' (length=6)
      'description' => string '' (length=0)
  5 => 
    array (size=5)
      'PermissionID' => string '5' (length=1)
      'parentId' => string '4' (length=1)
      'enabled' => string '1' (length=1)
      'title' => string '/visordre' (length=9)
      'description' => string '' (length=0)
```

_Example #2 Using the parent list mode_

```LESS
$permissions = $uarpc->permissions->list(['list'=>'parent']);

var_dump($permissions);
array (size=2)
  4 => 
    array (size=7)
      'PermissionID' => string '4' (length=1)
      'parentId' => null
      'enabled' => string '1' (length=1)
      'description' => string '' (length=0)
      'title' => string '/order' (length=6)
      'paTitle' => string '' (length=0)
      'elTitle' => string '/order' (length=6)
  5 => 
    array (size=7)
      'PermissionID' => string '5' (length=1)
      'parentId' => string '4' (length=1)
      'enabled' => string '1' (length=1)
      'description' => string '' (length=0)
      'title' => string '/order/visordre' (length=15)
      'paTitle' => string '/order' (length=6)
      'elTitle' => string '/visordre' (length=9)
```

<hr>
<hr>

## <dd>**permissions->getTitle**</dd>

Return the name of a permission, the function is available from the $uarpc object.

#### **Description**

    $uarpc->permissions->getTitle( int $PermissionID ) : string

#### **Parameters**

_PermissionID_  
    PermissionID you want to look up  

#### **Return Values**

Returns the name of the permission.

#### **Examples**

_Example #1 return the PermissionId for the admins role_

    echo $uarpc->permissions->getTitle(2);
    // example outputs: /invoice/write_access

<hr>
<hr>

## <dd>**permissions->getDescription**</dd>

Return the description of a permission, the function is available from the $uarpc object.

#### **Description**

    $uarpc->permissions->getDescription( int $PermissionID ) : string

#### **Parameters**

_PermissionID_  
    PermissionID you want to look up  

#### **Return Values**

Returns the name of the permission.

#### **Examples**

_Example #1 return the description for PermissionID 2

    echo $uarpc->permissions->getDescription(2);
    // example outputs: User can edit and write invoices

