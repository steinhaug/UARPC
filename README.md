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
