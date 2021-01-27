# USERS OBJECT FUNCTIONS

The users object has several functions for administering the users.

    $uarpc->users->deny( int $PermissionID, ? int $UserID ) : boolean
    $uarpc->users->undeny( int $PermissionID, ? int $UserID ) : boolean
    $uarpc->users->allow( int $PermissionID, ? int $UserID ) : boolean
    $uarpc->users->unallow( int $PermissionID, ? int $UserID ) : boolean

    $uarpc->users->list() : [PermID=>PermTitle]
    $uarpc->users->permissions(? $UserID) : [PermID=>PermTitle]
    $uarpc->users->roles(? $UserID) : [RoleID=>[Role data]]

## <dd>**users->deny**</dd>

Deny a user one particular permission, the function is available from the $uarpc object.

This method is for overriding the default role model and making tweaking and customized abilities possible.

### **Description**

    $uarpc->users->deny( int $PermissionID, int $UserID=null ) : boolean

### **Parameters**

_PermissionID_  
    The PermissionID for the permission you want to deny  
_UserID_  
    UserID from your own application logic, outside UARPC. Optional, or will use the instatianated UserID from class.  

### **Return Values**

On success returns true else returns false.

### **Examples**

_Example #1 Denying a permission from a user_

    $uarpc = new UARPC_base(999);
    $PermissionID = $uarpc->permissions->id('/write_access');
    $uarpc->users->deny($PermissionID);

<hr>
<hr>

## <dd>**users->undeny**</dd>

Removes a denied permission from a user, the function is available from the $uarpc object.

This method is for overriding the default role model and making tweaking and customized abilities possible.

### **Description**

    $uarpc->users->undeny( int $PermissionID, int $UserID=null ) : boolean

### **Parameters**

_PermissionID_  
    The PermissionID for the permission you want to undeny  
_UserID_  
    UserID from your own application logic, outside UARPC. Optional, or will use the instatianated UserID from class.  

### **Return Values**

On success returns true else returns false.

### **Examples**

_Example #1 Undenying a permission from a user_

    $uarpc = new UARPC_base(999);
    $PermissionID = $uarpc->permissions->id('/write_access');
    $uarpc->users->undeny($PermissionID);

<hr>
<hr>

## <dd>**users->allow**</dd>

Allow a user one particular permission, the function is available from the $uarpc object.

This method is for overriding the default role model and making tweaking and customized abilities possible.

### **Description**

    $uarpc->users->allow( int $PermissionID, int $UserID=null ) : boolean

### **Parameters**

_PermissionID_  
    The PermissionID for the permission you want to allow  
_UserID_  
    UserID from your own application logic, outside UARPC. Optional, or will use the instatianated UserID from class.  

### **Return Values**

On success returns true else returns false.

### **Examples**

_Example #1 Allowing a permission for a user_

    $uarpc = new UARPC_base(999);
    $PermissionID = $uarpc->permissions->id('/write_access');
    $uarpc->users->allow($PermissionID);

<hr>
<hr>

## <dd>**users->unallow**</dd>

Removes an allowed permission from a user, the function is available from the $uarpc object.

This method is for overriding the default role model and making tweaking and customized abilities possible.

### **Description**

    $uarpc->users->unallow( int $PermissionID, int $UserID=null ) : boolean

### **Parameters**

_PermissionID_  
    The PermissionID for the permission you want to unallow  
_UserID_  
    UserID from your own application logic, outside UARPC. Optional, or will use the instatianated UserID from class.  

### **Return Values**

On success returns true else returns false.

### **Examples**

_Example #1 Unallowing a permission from a user_

    $uarpc = new UARPC_base(999);
    $PermissionID = $uarpc->permissions->id('/write_access');
    $uarpc->users->unallow($PermissionID);

<hr>
<hr>

## <dd>**users->list**</dd>

Alias for users->permissions

<hr>
<hr>

## <dd>**users->permissions**</dd>

List all permissions belonging to a user.

#### **Description**

    $uarpc->users->permissions(? $UserID) : array

#### **Parameters**

_UserID_  
    UserID from your own application logic, outside UARPC. Optional, or will use the instatianated UserID from class.  

#### **Return Values**

An associated array: PermissionID => Permission_title.

#### **Examples**

_Example #1 Listing permissions for default user_

```LESS
$permissions = $uarpc->users->permissions();

var_dump($permissions);
array (size=3)
    5 => string '/visordre' (length=9)
    6 => string '/regnskap' (length=9)
    7 => string '/skrive' (length=7)
```
<hr>
<hr>

## <dd>**users->roles**</dd>

List all roles belonging to a user.

#### **Description**

    $uarpc->users->roles(? $UserID) : array

#### **Parameters**

_UserID_  
    UserID from your own application logic, outside UARPC. Optional, or will use the instatianated UserID from class.  

#### **Return Values**

An associated array: RoleID => [RoleID, title, description].

#### **Examples**

_Example #1 Listing roles for default user_

```LESS
// Example, user belonging to "admin" and "bruker".
$roles = $uarpc->users->roles();

var_dump($roles);
array (size=2)
  1 => 
    array (size=3)
      'RoleID' => int 1
      'title' => string 'admin' (length=5)
      'description' => string '' (length=0)
  2 => 
    array (size=3)
      'RoleID' => int 2
      'title' => string 'bruker' (length=6)
      'description' => string '' (length=0)
```
