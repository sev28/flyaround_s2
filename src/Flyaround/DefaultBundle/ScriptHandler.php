<?php

namespace Flyaround\DefaultBundle;

use Composer\Script\Event;

class ScriptHandler
{
    public static function jwtInstall()
    {
        $projectRoot = __DIR__."/../../..";
        if (!file_exists($projectRoot."/app/var/jwt/private.pem") || !file_exists($projectRoot."/app/var/jwt/public.pem")){
            if (!is_dir($projectRoot."/app/var"))
                shell_exec("mkdir ".$projectRoot."/app/var");
            if (!is_dir($projectRoot."/app/var/jwt"))
                shell_exec("mkdir ".$projectRoot."/app/var/jwt");
            shell_exec("openssl genrsa -out app/var/jwt/private.pem -aes256 4096");
            shell_exec("openssl rsa -pubout -in app/var/jwt/private.pem -out app/var/jwt/public.pem");
        }
    }
}
