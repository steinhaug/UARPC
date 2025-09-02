# Project Overview

This project is a PHP library named UARPC (User Access, Roles, Permissions and Configurations) that provides a framework for managing user access control. It allows developers to define roles and permissions, assign roles to users, and check if a user has a specific permission. The library uses a MySQL database to store the relationships between users, roles, and permissions.

## Main Technologies

*   **Language:** PHP >= 8.0.0
*   **Database:** MySQL
*   **Dependency Management:** Composer

## Architecture

The library is composed of a main class `UARPC_base` which is the entry point for all interactions. It also includes three manager classes:

*   `UARPC_PermissionManager`: Manages permissions, including creating, deleting, editing, enabling/disabling, and assigning them to roles.
*   `UARPC_RoleManager`: Manages roles, including creating, deleting, editing, and assigning them to users.
*   `UARPC_UserManager`: Manages user-specific permissions, allowing for overrides (allow/deny) and listing a user's effective permissions.

The core functionality is centered around the `havePermission` method in the `UARPC_base` class, which checks for permissions in the following order:

1.  Checks if the permission is enabled.
2.  Checks if the permission is denied for the user (override).
3.  Checks if the permission is allowed for the user (override).
4.  Checks if the permission is allowed for any of the user's roles.

# Building and Running

## Installation

To install the library, use Composer:

```bash
composer require steinhaug/uarpc
```

## Initialization

To use the library, you need to initialize the `uarpc` and `UARPC_base` classes:

```php
new uarpc(opt 'table_prefix'); // needed for loading uarpc classes
$uarpc = new UARPC_base($UserId);
```

The `uarpc` class handles the database setup. If the required tables are not found, it will provide instructions on how to create them.

## Testing

There is a `test/testuarpc.php` file, but no explicit testing framework is defined in `composer.json`. To run the tests, you would likely need to execute the `test/testuarpc.php` file in a PHP environment.

# Development Conventions

*   The code follows the PSR-0 autoloading standard.
*   The code is organized into classes with specific responsibilities (e.g., `UARPC_PermissionManager`, `UARPC_RoleManager`, `UARPC_UserManager`).
*   The library uses a global `$mysqli` variable for database connections.
*   The library uses a database table prefix to avoid naming conflicts.
*   The library has a verbose mode for debugging purposes.
