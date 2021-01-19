# ROLES OBJECT FUNCTIONS

The roles object has several functions for administering the roles.

    $uarpc->roles->add( string $title, string $description ) : int
    $uarpc->roles->assign( int $RoleID, int $UserID ) : boolean
    $uarpc->roles->unassign( int $RoleID, int $UserID ) : boolean
    $uarpc->roles->id( string $title ) : int
    $uarpc->roles->getTitle( int $RoleID ) : string
    $uarpc->roles->getDescription( int $RoleID ) : string

    $uarpc->roles->delete($RoleID) : bool
    $uarpc->roles->id($title) : RoleID
    $uarpc->roles->edit($RoleID, $title, ? $description) : bool
    $uarpc->roles->list() : [RoleID=>[obj]]

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

## <dd>**roles->unassign**</dd>

un-Assign a user to a role, the function is available from the $uarpc object.

### **Description**

    $uarpc->roles->unassign( int $RoleID, int $UserID ) : boolean
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
    $uarpc->roles->unassign($roleid, 490);

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

## <dd>**roles->getTitle**</dd>

Return the name of a role, the function is available from the $uarpc object.

### **Description**

    $uarpc->roles->getTitle( int $RoleID ) : string

### **Parameters**

_RoleID_
    RoleID you want to look up

### **Return Values**

Returns the name of the role.

### **Examples**

_Example #1 return name of RoleID 2_

    echo $uarpc->roles->getTitle(2);
    // example outputs: Admins

## <dd>**roles->getDescription**</dd>

Return the description of a role, the function is available from the $uarpc object.

### **Description**

    $uarpc->roles->getDescription( int $RoleID ) : string

### **Parameters**

_RoleID_
    RoleID you want to look up

### **Return Values**

Returns the description of a role.

### **Examples**

_Example #1 return the description for RoleID 2

    echo $uarpc->roles->getDescription(2);
    // example outputs: Administrative group all allowed

