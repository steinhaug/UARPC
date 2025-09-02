# UARPC_PermissionManager

The `UARPC_PermissionManager` class provides methods for managing permissions.

## Methods

### `add($title, $description = '', $parentId = null, $enabled = 1)`

Adds a new permission to the system.

*   `$title`: The title of the permission.
*   `$description`: A description of the permission.
*   `$parentId`: The ID of the parent permission.
*   `$enabled`: A boolean that determines whether the permission is enabled.

Returns the ID of the new permission.

### `delete($PermissionID)`

Deletes a permission from the system.

*   `$PermissionID`: The ID of the permission to delete.

### `edit($PermissionID, $title, $description = '', $parentId = null, $enabled = null)`

Edits an existing permission.

*   `$PermissionID`: The ID of the permission to edit.
*   `$title`: The new title of the permission.
*   `$description`: The new description of the permission.
*   `$parentId`: The new parent ID of the permission.
*   `$enabled`: A boolean that determines whether the permission is enabled.

### `state($PermissionID)`

Gets the state of a permission.

*   `$PermissionID`: The ID of the permission.

Returns `true` if the permission is enabled, and `false` otherwise.

### `enable($PermissionID)`

Enables a permission.

*   `$PermissionID`: The ID of the permission to enable.

### `disable($PermissionID)`

Disables a permission.

*   `$PermissionID`: The ID of the permission to disable.

### `assign($PermissionID, $RoleID)`

Assigns a permission to a role.

*   `$PermissionID`: The ID of the permission.
*   `$RoleID`: The ID of the role.

### `unassign($PermissionID, $RoleID)`

Unassigns a permission from a role.

*   `$PermissionID`: The ID of the permission.
*   `$RoleID`: The ID of the role.

### `id($title)`

Gets the ID of a permission from its title.

*   `$title`: The title of the permission.

Returns the ID of the permission, or `false` if the permission does not exist.

### `list($RoleID = null)`

Lists all permissions, or all permissions assigned to a specific role.

*   `$RoleID`: The ID of the role. If not provided, all permissions will be listed.

Returns an array of permissions.

### `listUser($conf)`

Lists all permissions for a user, including overrides.

*   `$conf`: An array of configuration options.

Returns an array of permissions.

### `getTitle($PermissionID)`

Gets the title of a permission.

*   `$PermissionID`: The ID of the permission.

Returns the title of the permission.

### `getDescription($PermissionID)`

Gets the description of a permission.

*   `$PermissionID`: The ID of the permission.

Returns the description of the permission.
