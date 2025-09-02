# UARPC_base

The `UARPC_base` class is the main class of the UARPC library. It provides the core functionality for managing and checking user permissions.

## Properties

*   `$roles`: An instance of the `UARPC_RoleManager` class.
*   `$permissions`: An instance of the `UARPC_PermissionManager` class.
*   `$users`: An instance of the `UARPC_UserManager` class.
*   `$UserID`: The ID of the user that the `UARPC_base` instance is associated with.
*   `$db_prefix`: The prefix for the database tables used by UARPC.
*   `$verbose_actions`: A boolean that determines whether to output debugging information.

## Methods

### `__construct($UserID = null, $verbose_actions = false, $db_prefix = null)`

Initializes a new instance of the `UARPC_base` class.

*   `$UserID`: The ID of the user to associate with the `UARPC_base` instance.
*   `$verbose_actions`: A boolean that determines whether to output debugging information.
*   `$db_prefix`: The prefix for the database tables used by UARPC.

### `set_db_prefix($db_prefix)`

Sets the prefix for the database tables used by UARPC.

*   `$db_prefix`: The new prefix for the database tables.

### `addRole($title, $description = '')`

Adds a new role to the system.

*   `$title`: The title of the role.
*   `$description`: A description of the role.

Returns the ID of the new role.

### `addPermission($title, $description = '')`

Adds a new permission to the system.

*   `$title`: The title of the permission.
*   `$description`: A description of the permission.

Returns the ID of the new permission.

### `assignRole($RoleID, $UserID)`

Assigns a role to a user.

*   `$RoleID`: The ID of the role.
*   `$UserID`: The ID of the user.

### `assignPermission($PermissionID, $RoleID)`

Assigns a permission to a role.

*   `$PermissionID`: The ID of the permission.
*   `$RoleID`: The ID of the role.

### `havePermission($PermissionTitle, $UserID = null)`

Checks if a user has a specific permission.

*   `$PermissionTitle`: The title of the permission.
*   `$UserID`: The ID of the user. If not provided, the `UserID` property of the `UARPC_base` instance will be used.

Returns `true` if the user has the permission, and `false` otherwise.

### `permEnabled($PermissionTitle)`

Checks if a permission is enabled.

*   `$PermissionTitle`: The title of the permission.

Returns `true` if the permission is enabled, and `false` otherwise.
