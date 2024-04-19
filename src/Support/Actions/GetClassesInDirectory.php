<?php

namespace Javaabu\Paperless\Support\Actions;


class GetClassesInDirectory
{

    public function handle(string $directory, string $namespace): array
    {
        $classes = [];

        // Open the directory
        if ($handle = opendir($directory)) {
            // Loop through each file in the directory
            while (false !== ($file = readdir($handle))) {
                // Check if the file is a PHP file
                if (pathinfo($file, PATHINFO_EXTENSION) === "php") {
                    // Read the contents of the PHP file
                    $contents = file_get_contents($directory . "/" . $file);

                    // Find all class declarations in the file
                    preg_match_all("/\bclass\s+(\w+)/", $contents, $matches);

                    // Add each class name to the array
                    foreach ($matches[1] as $className) {
                        $classes[] = $namespace . '\\' . $className;
                    }
                }
            }
            // Close the directory handle
            closedir($handle);
        }

        // Return the array of class names
        return $classes;
    }
}
