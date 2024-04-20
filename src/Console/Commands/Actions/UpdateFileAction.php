<?php

namespace Javaabu\Paperless\Console\Commands\Actions;

class UpdateFileAction
{
    public function handle(
        string $file_path,
        string $content_to_insert,
        string $content_to_check,
        string $content_to_insert_after,
    ): bool {
        // get the content of the file
        $admin_routes_content = file_get_contents($file_path);

        // check if the content already exists
        if (str_contains($admin_routes_content, $content_to_check)) {
            return true;
        }

        // find the position of the content to insert after
        $position = strpos($admin_routes_content, $content_to_insert_after);
        if (! $position) {
            return false;
        }

        // insert the content after the position
        $admin_routes_content = substr_replace(
            $admin_routes_content,
            $content_to_insert,
            $position + strlen($content_to_insert_after),
            0
        );

        // save the content back to the file
        file_put_contents($file_path, $admin_routes_content);
        return true;
    }
}
