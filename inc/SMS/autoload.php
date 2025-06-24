<?php



    spl_autoload_register(function ($class) {
     
        // project-specific namespace prefix
       

        // base directory for the namespace prefix
       // $base_dir ='/src/IPPanel/';
       

        // does the class use the namespace prefix?
       
       /* if (strncmp($prefix, $class, $len) !== 0) {
			echo "test2222222";
            // no, move to the next registered autoloader
            return;
        }*/

        // get the relative class name
       
		//echo $class;

        // replace the namespace prefix with the base directory, replace namespace
        // separators with directory separators in the relative class name, append
        // with .php
        $file = str_replace('\\', '/', $class) . '.php';
       
        // if the file exists, require it
		
		
       if (file_exists('inc/'.$file)) {
		   
            require 'inc/'.$file;
			die();
        }
		 echo "not_req";
    });



