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
