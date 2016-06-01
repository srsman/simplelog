<?php
//ROOT_PATH常量是某些库依赖的，例如用来根据路径生成url
define('ROOT_PATH', realpath(dirname(__FILE__)).DIRECTORY_SEPARATOR);
//载入
require_once( ROOT_PATH.'boot/bootstrap.php' );

