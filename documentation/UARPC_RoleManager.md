# UARPC_RoleManager

The `UARPC_RoleManager` class provides methods for managing roles.

## Methods

### `add($title, $description = '')`

Adds a new role to the system.

*   `$title`: The title of the role.
*   `$description`: A description of the role.

Returns the ID of the new role.

### `delete($RoleID)`

Deletes a role from the system.

*   `$RoleID`: The ID of the role to delete.

### `edit($RoleID, $title, $description = '')`

Edits an existing role.

*   `$RoleID`: The ID of the role to edit.
*   `$title`: The new title of the role.
*   `$description`: The new description of the role.

### `assign($RoleID, $UserID)`

Assigns a role to a user.

*   `$RoleID`: The ID of the role.
*   `$UserID`: The ID of the user.

### `unassign($RoleID, $UserID)`

Unassigns a role from a user.

*   `$RoleID`: The ID of the role.
*   `$UserID`: The ID of the user.

### `isAssigned($RoleID, $UserID)`

Checks if a user is assigned to a role.

*   `$RoleID`: The ID of the role.
*   `$UserID`: The ID of the user.

Returns `true` if the user is assigned to the role, and `false` otherwise.

### `id($title)`

Gets the ID of a role from its title.

*   `$title`: The title of the role.

Returns the ID of the role, or `false` if the role does not exist.

### `list($UserID = null)`

Lists all roles, or all roles assigned to a specific user.

*   `$UserID`: The ID of the user. If not provided, all roles will be listed.

Returns an array of roles.

### `listUsers($RoleID)`

Lists all users assigned to a specific role.

*   `$RoleID`: The ID of the role.

Returns an array of users.

### `getTitle($RoleID)`

Gets the title of a role.

*   `$RoleID`: The ID of the role.

Returns the title of the role.

### `getDescription($RoleID)`

Gets the description of a role.

*   `$RoleID`: The ID of the role.

Returns the description of the role.
