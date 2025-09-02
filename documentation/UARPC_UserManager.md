# UARPC_UserManager

The `UARPC_UserManager` class provides methods for managing user-specific permissions.

## Methods

### `allow($PermissionID, $UserID = null)`

Allows a user to have a specific permission, overriding any role-based permissions.

*   `$PermissionID`: The ID of the permission.
*   `$UserID`: The ID of the user. If not provided, the `UserID` property of the `UARPC_UserManager` instance will be used.

### `unallow($PermissionID, $UserID = null)`

Removes a user's permission to have a specific permission.

*   `$PermissionID`: The ID of the permission.
*   `$UserID`: The ID of the user. If not provided, the `UserID` property of the `UARPC_UserManager` instance will be used.

### `deny($PermissionID, $UserID = null)`

Denies a user from having a specific permission, overriding any role-based permissions.

*   `$PermissionID`: The ID of the permission.
*   `$UserID`: The ID of the user. If not provided, the `UserID` property of the `UARPC_UserManager` instance will be used.

### `undeny($PermissionID, $UserID = null)`

Removes a user's denial to have a specific permission.

*   `$PermissionID`: The ID of the permission.
*   `$UserID`: The ID of the user. If not provided, the `UserID` property of the `UARPC_UserManager` instance will be used.

### `isAllowed($PermissionID, $UserID = null)`

Checks if a user is allowed to have a specific permission.

*   `$PermissionID`: The ID of the permission.
*   `$UserID`: The ID of the user. If not provided, the `UserID` property of the `UARPC_UserManager` instance will be used.

Returns `true` if the user is allowed to have the permission, and `false` otherwise.

### `isDenied($PermissionID, $UserID = null)`

Checks if a user is denied from having a specific permission.

*   `$PermissionID`: The ID of the permission.
*   `$UserID`: The ID of the user. If not provided, the `UserID` property of the `UARPC_UserManager` instance will be used.

Returns `true` if the user is denied from having the permission, and `false` otherwise.

### `list($UserID = null)`

Lists all permissions for a user, including overrides.

*   `$UserID`: The ID of the user. If not provided, the `UserID` property of the `UARPC_UserManager` instance will be used.

Returns an array of permissions.

### `permissions($UserID = null)`

Lists all permissions for a user, including overrides.

*   `$UserID`: The ID of the user. If not provided, the `UserID` property of the `UARPC_UserManager` instance will be used.

Returns an array of permissions.

### `roles($UserID = null)`

Lists all roles for a user.

*   `$UserID`: The ID of the user. If not provided, the `UserID` property of the `UARPC_UserManager` instance will be used.

Returns an array of roles.
