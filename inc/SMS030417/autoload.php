<?php
echo "test....";
if (file_exists(__DIR__.'/autoload.php')) {
    require_once __DIR__.'/autoload.php';
} else {
    spl_autoload_register(function ($class) {
        echo 1;
        // project-specific namespace prefix
        $prefix = 'IPPanel\\';

        // base directory for the namespace prefix
        $base_dir = __DIR__ . '/src/IPPanel/';
        echo $base_dir;

        // does the class use the namespace prefix?
        $len = strlen($prefix);
        if (strncmp($prefix, $class, $len) !== 0) {
            echo $base_dir;
            // no, move to the next registered autoloader
            return;
        }

        // get the relative class name
        $relative_class = substr($class, $len);

        // replace the namespace prefix with the base directory, replace namespace
        // separators with directory separators in the relative class name, append
        // with .php
        $file = $base_dir . str_replace('\\', '/', $relative_class) . '.php';
        error_log('uuuurrrrllll'.$base_dir);
        // if the file exists, require it
        if (file_exists($file)) {
            require $file;
        }
    });
}
