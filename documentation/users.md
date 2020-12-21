# USERS OBJECT FUNCTIONS

The users object has several functions for administering the users.

## <dd>**users->deny**</dd>

Deny a user one particular permission, the function is available from the $uarpc object.

This method is for overriding the default role model and making tweaking and customized abilities possible.

### **Description**

    $uarpc->users->deny( int $PermissionID, int $UserID=null ) : boolean

### **Parameters**

_PermissionID_  
    The PermissionID for the permission you want to deny  
_UserID_  
    UserID from your own application logic, outside UARPC. Optional, or will use the instatianated UsedID from class.  

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
    UserID from your own application logic, outside UARPC. Optional, or will use the instatianated UsedID from class.  

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
    UserID from your own application logic, outside UARPC. Optional, or will use the instatianated UsedID from class.  

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
    UserID from your own application logic, outside UARPC. Optional, or will use the instatianated UsedID from class.  

### **Return Values**

On success returns true else returns false.

### **Examples**

_Example #1 Unallowing a permission from a user_

    $uarpc = new UARPC_base(999);
    $PermissionID = $uarpc->permissions->id('/write_access');
    $uarpc->users->unallow($PermissionID);

<hr>
<hr>
