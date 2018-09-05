<?php
namespace Esmi\utils;

class PhpInfo {
    /**
     * @var string
     */
    private $release;
    /**
     * @var string
     */
    private $version;

    public function __construct() {
        $this->getVersion();
    }
    public function getVersion(): string

    {
        if ($this->version === null) {
            $this->version = $this->getPhpVersion();
        }
        return $this->version;
    }

    private function getPhpVersion() {

        if (!defined('PHP_VERSION_ID')) {
            $version = explode('.', PHP_VERSION);

            define('PHP_VERSION_ID', ($version[0] * 10000 + $version[1] * 100 + $version[2]));
        }

        if (PHP_VERSION_ID < 50207) {
            define('PHP_MAJOR_VERSION',   $version[0]);
            define('PHP_MINOR_VERSION',   $version[1]);
            define('PHP_RELEASE_VERSION', $version[2]);

        }
        return PHP_VERSION_ID;
    }

    public function isOldMysql() {
        return  ($this->version < '54') ? true: false;
    }

    public function vendorPath() {
        $cwd = getcwd();
        if (is_dir(__DIR__ . "/../vendor"))
            return __DIR__ . "/..";
        if (is_dir(__DIR__ . "./vendor"))
            return __DIR__ . "/.";
        if ( is_dir($cwd . "/../vendor"))
            return $cwd . "/..";
        if ( is_dir($cwd . "/vendor"))
            return $cwd ;
        return "";
    }

    public function oldMysqlSyntaxCompitable($oldSyntax = true) {
        if ( !$this->isOldMysql() && $oldSyntax ) {
            //echo "old mySQL!!!";
            //echo $this->vendorPath();
            require $this->vendorPath() . '/vendor/autoload.php';
        	\Mattbit\MysqlCompat\Mysql::defineGlobals();
        }
    }
}
