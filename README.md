# UARPC - User Access, Roles, Permissions and Configurations

UARPC is a PHP library that provides a flexible and easy-to-use framework for managing user access control. It allows you to define roles and permissions, assign roles to users, and check if a user has a specific permission.

<div class="show_none">

# Table of Contents

- [UARPC - User Access, Roles, Permissions and Configurations](#uarpc---user-access-roles-permissions-and-configurations)
- [Table of Contents](#table-of-contents)
- [1. INSTALL](#1-install)
  - [Getting Started](#getting-started)
- [2. DOCUMENTATION](#2-documentation)
  - [Basic Usage](#basic-usage)
- [3. BRIEF HISTORY LOG](#3-brief-history-log)
  - [UARPC v1.6.5](#uarpc-v165)
  - [UARPC v1.6.4](#uarpc-v164)
  - [UARPC v1.6.3](#uarpc-v163)
  - [UARPC v1.6.2](#uarpc-v162)
  - [UARPC v1.6.1](#uarpc-v161)
- [4. Information](#4-information)
  - [4.1 License](#41-license)
  - [4.2 Author](#42-author)
</div>

# 1. INSTALL

To install the library, use Composer:

```bash
composer require steinhaug/uarpc
```

## Getting Started

To start using UARPC, you need to initialize the `uarpc` and `UARPC_base` classes:

```php
require 'vendor/autoload.php';

// Initialize the uarpc class to ensure all required files are loaded
new uarpc();

// Create a new UARPC_base instance with a user ID
$uarpc = new UARPC_base($userID);
```

# 2. DOCUMENTATION

*   [UARPC_base](documentation/UARPC_base.md)
*   [UARPC_PermissionManager](documentation/UARPC_PermissionManager.md)
*   [UARPC_RoleManager](documentation/UARPC_RoleManager.md)
*   [UARPC_UserManager](documentation/UARPC_UserManager.md)

## Basic Usage

Here's a simple example of how to use UARPC to manage roles and permissions:

```php
// Create a new role
$roleID = $uarpc->roles->add('Administrator', 'This is the administrator role');

// Create a new permission
$permissionID = $uarpc->permissions->add('create-post', 'Allows a user to create a new post');

// Assign the permission to the role
$uarpc->permissions->assign($permissionID, $roleID);

// Assign the role to a user
$uarpc->roles->assign($roleID, $userID);

// Check if the user has the permission
if ($uarpc->havePermission('create-post', $userID)) {
    echo 'The user has permission to create a post.';
} else {
    echo 'The user does not have permission to create a post.';
}
```

# 3. BRIEF HISTORY LOG

See the commit log for updates.

## UARPC v1.6.5

Updated readme.

## UARPC v1.6.4

Added \$GLOBALS['steinhaugUarpcDbPrefix'] for all classes.

## UARPC v1.6.3

Fixed composer requirements.

## UARPC v1.6.2

Added UserID parameter so it's possible to check against any user.  
->havePermission(PermTitle, UserID),

## UARPC v1.6.1

Moved Mysqli2 into seperate project

# 4. Information

## 4.1 License

This project is licensed under the terms of the  [MIT](http://www.opensource.org/licenses/mit-license.php) License. Enjoy!

## 4.2 Author

Kim Steinhaug, steinhaug at gmail dot com.

**Sosiale lenker:**
[LinkedIn](https://www.linkedin.com/in/steinhaug/), [SoundCloud](https://soundcloud.com/steinhaug), [Instagram](https://www.instagram.com/steinhaug), [Youtube](https://www.youtube.com/@kimsteinhaug), [X](https://x.com/steinhaug), [Ko-Fi](https://ko-fi.com/steinhaug), [Github](https://github.com/steinhaug), [Gitlab](https://gitlab.com/steinhaug)

**Generative AI lenker:**
[Udio](https://www.udio.com/creators/Steinhaug), [Suno](https://suno.com/@steinhaug), [Huggingface](https://huggingface.co/steinhaug)

**Resurser og hjelpesider:**
[Linktr.ee/steinhaugai](https://linktr.ee/steinhaugai), [Linktr.ee/stainhaug](https://linktr.ee/stainhaug), [pinterest/steinhaug](https://no.pinterest.com/steinhaug/), [pinterest/stainhaug](https://no.pinterest.com/stainhaug/)
