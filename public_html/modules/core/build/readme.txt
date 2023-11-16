Core
Current version: v2.6.0
Created by: Arjan van den Broek

=========================

All core functionality for frontend and backend (CMS) including
- imagemanagement, file-, link- and videolinkmanagement
- filemanagement
- linkmanagement
- videolinkmanagement
- crop
- login
- settings
- system translations
- usermanagement
- user access groups
- module management

=========================

How to install:
1. create database and import until latest version is installed
2. create local config and set database credentials and other settings
3. login to CMS and go to install module
4. check installation and upgrade/fix where needed

How to use invisible captcha:
1. Create a new recaptcha key here: https://www.google.com/recaptcha
2. Use our google account: sem@landgoedvoorn.nl
3. !!!! IMPORTANT !!!! you need to add all url's it's used on in the google console, for example: loc.a-cms.nl , a-cms.dev.landgoedvoorn.online , a-cms.nl ... If you don't do this, it will most likely not work.
4. Enter the website and secret key in the settings module, this will activate the Captcha module

<link rel="stylesheet" type="text/css" href="//cdnjs.cloudflare.com/ajax/libs/cookieconsent2/3.1.0/cookieconsent.min.css" />
<script src="//cdnjs.cloudflare.com/ajax/libs/cookieconsent2/3.1.0/cookieconsent.min.js"></script>
<script>