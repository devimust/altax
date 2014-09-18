<?php
namespace Altax\Script;

/**
 * ScriptHandler
 */
class ScriptHandler
{
    const CONTENT_OF_AUTOLOAD_FILES = <<<EOL
<?php

// autoload_files.php @modified by Altax ScriptHandler

\$vendorDir = dirname(dirname(__FILE__));
\$baseDir = dirname(\$vendorDir);

return array(
    \$vendorDir . '/illuminate/support/Illuminate/Support/helpers.php',
//    \$vendorDir . '/phpseclib/phpseclib/phpseclib/Crypt/Random.php',
    \$baseDir . '/src/Altax/Foundation/functions.php',
);

EOL;

     public static $autoloadBaseDir = null;

    /**
     * remove autoload configuration in autoload_files.php generated by composer.
     * 
     * autoload_files.php includes '/phpseclib/phpseclib/phpseclib/Crypt/Random.php'.
     * and composer autoloader defines `crypt_random_string` function at initialize process.
     *
     * If you load phpseclib in a altax task configuration files (ex `.altax/config.php`)
     * using composer autoloading. You will get a error 
     * `PHP Fatal error:  Cannot redeclare crypt_random_string()`.
     * In order to prevent the error. remove this settings.
     *
     * @return [type] [description]
     */
    public static function removeAutoloadFiles()
    {
        $autoloadFile = realpath(__DIR__."/../../../vendor/composer/autoload_files.php");

        // Back up original file.
        copy($autoloadFile, dirname($autoloadFile)."/autoload_files.original.php");

        file_put_contents(
            $autoloadFile, 
            self::CONTENT_OF_AUTOLOAD_FILES
        );
    }
}
