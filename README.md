# vulna-wiki
Really unsecure wiki-application for security vulnerability demonstrations.

## Login page /login.php
### username
**' OR 1=1;--** Defaults to first user account in system, which *usually* is admin account
### password
The field is checked in code, there shouldn't be vulnerability here.

## /signup.php
### username
Field has no checks, should be vulnerable.
### password
Field has no checks, should be vulnerable.
### invitation code
Field has no checks, should be vulnerable.


