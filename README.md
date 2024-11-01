# UARPC

User Access, Roles, Permissions and Configurations framework
Tools for administering user access. 

https://gitlab.com/steinhaug/uarpc/-/wikis/home
https://lucid.app/lucidspark/invitations/accept/19b35103-eaa4-4b0c-82c4-c124a1ac8880

<div class="show_none">

# Table of Contents

- [UARPC](#uarpc)
- [Table of Contents](#table-of-contents)
- [1. INSTALL](#1-install)
  - [1.1 COMPOSER](#11-composer)
  - [1.2 INIT LIBRARY](#12-init-library)
- [2. DOCUMENTATION](#2-documentation)
- [3. REQUIREMENTS](#3-requirements)
- [4. BRIEF HISTORY LOG](#4-brief-history-log)
  - [UARPC v1.6.5](#uarpc-v165)
  - [UARPC v1.6.4](#uarpc-v164)
  - [UARPC v1.6.3](#uarpc-v163)
  - [UARPC v1.6.2](#uarpc-v162)
  - [UARPC v1.6.1](#uarpc-v161)
- [5. Information](#5-information)
  - [5.1 License](#51-license)
  - [5.2 Author](#52-author)
</div>

# 1. INSTALL

## 1.1 COMPOSER

To install the library use composer:

    composer require steinhaug/uarpc

    or specify version

    composer require "steinhaug/uarpc:^1.*"

## 1.2 INIT LIBRARY

    new uarpc(opt 'table_prefix'); // needed for loading uarpc classes
    $uarpc = new UARPC_base($UserId);
    ... see docs for rest

# 2. DOCUMENTATION

Use the [WIKI pages documentation](https://gitlab.com/steinhaug/uarpc/-/wikis/home) or the markdown files inside the /docs folder.

# 3. REQUIREMENTS

Manually add /dist folder for local development.

# 4. BRIEF HISTORY LOG

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

# 5. Information

## 5.1 License

This project is licensed under the terms of the  [MIT](http://www.opensource.org/licenses/mit-license.php) License. Enjoy!

## 5.2 Author

Kim Steinhaug, steinhaug at gmail dot com.

**Sosiale lenker:**
[LinkedIn](https://www.linkedin.com/in/steinhaug/), [SoundCloud](https://soundcloud.com/steinhaug), [Instagram](https://www.instagram.com/steinhaug), [Youtube](https://www.youtube.com/@kimsteinhaug), [X](https://x.com/steinhaug), [Ko-Fi](https://ko-fi.com/steinhaug), [Github](https://github.com/steinhaug), [Gitlab](https://gitlab.com/steinhaug)

**Generative AI lenker:**
[Udio](https://www.udio.com/creators/Steinhaug), [Suno](https://suno.com/@steinhaug), [Huggingface](https://huggingface.co/steinhaug)

**Resurser og hjelpesider:**
[Linktr.ee/steinhaugai](https://linktr.ee/steinhaugai), [Linktr.ee/stainhaug](https://linktr.ee/stainhaug), [pinterest/steinhaug](https://no.pinterest.com/steinhaug/), [pinterest/stainhaug](https://no.pinterest.com/stainhaug/)
