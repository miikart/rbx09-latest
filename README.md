# goldblox-latest
![enter image description here](https://raw.githubusercontent.com/miikart/rbx09-latest/refs/heads/main-src/images/GOLDBLOX.png)
goldblox WAS an january 2009 'website' recreation
# Requirements
 - Nginx(Apache might work..)
 - MySQL
 - Windows/WSL/Linux
 - As least php 8.x,
 - Windows machine/VM for Rendering(Wine might work?)
 - [2008 RCC.](https://archive.robloxopolis.com/files//Clients/RBXGS)

# Quick Start Guide (QSG)
0.  (NGINX ONLY) put  
>  

      location / {
        try_files $uri $uri/ @php;
    }
    location @php {
        rewrite ^/(.*)$ /$1.php last;
    }
    location ~ \.(aspx|ashx)$ {
        rewrite ^/(.*)\.(aspx|ashx)$ /$1.php last;
    }
    if ($host ~* ^[^www\.](.*)) {
        return 302 http://www.$host$request_uri;
         }
         location ~ ^/api/web/ {
        deny all;
    }
         
in your nginx config for your GOLDBLOX rehost.

 1. import the db in /database in whatever database software you use.
 2. change URL in /api/web/config.php
 3. change db info in /api/web/database.php and dbpdo.php.
 4. change your recaptcha info in /recaptcha.php and in /Login/newage.php
 5. start render server.
 6. Done.
# Common issues that might appear during setup.
1. Rendering ( to fix this, you will have to do this yourself . )
2. 500 Error. ( 500 Occurs because you arent using CF(Cloudflare.).. )
# Fixes
1. Delete line 209 - Line 265 in api/web/config.php to fix 500.
 # Rendering ( good luck...)
0. The render URL for GOLDBLOX is http://localhost:30000, Please note that.
1. the character file that GOLDBLOX uses  can be found in [/Rendering/](https://github.com/miikart/rbx09-latest/tree/main-src/Rendering)
2. copy and paste everything in that folder to C:\ProgramData\Roblox\content\fonts 
3. Try rendering..
4. Done?
# GameServer
1. you will need to modify Character.rbxm if you are using 2008 RCC for GameServer.
2. Goldblox has no official support for any game server in any way. 
