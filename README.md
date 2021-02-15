# UARPC v1.5.x

User Access, Roles, Permissions and Configurations framework
Tools for administering user access. 

https://gitlab.com/steinhaug/uarpc/-/wikis/home

https://lucid.app/lucidspark/invitations/accept/19b35103-eaa4-4b0c-82c4-c124a1ac8880


## Install by composer

To install the library use composer:

    composer require steinhaug/uarpc

    or specify version

    composer require "steinhaug/uarpc:^1.*"

Make sure that project is running **class.mysqli.php** as this is required for UARPC to work. To initialize, use like this:

    new uarpc; // loading and $mysqli check
    $uarpc = new UARPC_base($UserId);
    ... see docs for rest

## Documentation

Use the [WIKI pages documentation](https://gitlab.com/steinhaug/uarpc/-/wikis/home) or the markdown files inside the /docs folder.

## Requirements

Manually add /dist folder for local development.
