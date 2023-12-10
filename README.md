# UARPC v1.6.4

User Access, Roles, Permissions and Configurations framework
Tools for administering user access. 

https://gitlab.com/steinhaug/uarpc/-/wikis/home

https://lucid.app/lucidspark/invitations/accept/19b35103-eaa4-4b0c-82c4-c124a1ac8880


## Install by composer

To install the library use composer:

    composer require steinhaug/uarpc

    or specify version

    composer require "steinhaug/uarpc:^1.*"

# init

    new uarpc(opt 'table_prefix'); // needed for loading uarpc classes
    $uarpc = new UARPC_base($UserId);
    ... see docs for rest

## Documentation

Use the [WIKI pages documentation](https://gitlab.com/steinhaug/uarpc/-/wikis/home) or the markdown files inside the /docs folder.

## Requirements

Manually add /dist folder for local development.


## -- brief history log --

### UARPC v1.6.4

Added \$GLOBALS['steinhaugUarpcDbPrefix'] for all classes.

### UARPC v1.6.3

Fixed composer requirements.

### UARPC v1.6.2

Added UserID parameter so it's possible to check against any user.  
->havePermission(PermTitle, UserID),

### UARPC v1.6.1

Moved Mysqli2 into seperate project
