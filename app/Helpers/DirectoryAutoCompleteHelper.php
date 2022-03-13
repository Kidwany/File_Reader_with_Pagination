<?php


namespace App\Helpers;


class DirectoryAutoCompleteHelper
{
    public $search_key;

    public function suggestDirectories($search_key = null)
    {
        $server_log_directory = config("app.log_directory_path");
        $all_sub_directories = array_diff(scandir($server_log_directory), array('.', '..'));
        if ($search_key && !empty($all_sub_directories))
        {
            $suggested_array = array();
            foreach ($all_sub_directories as $sub_directory)
            {
                if (str_contains($sub_directory, $search_key))
                    array_push($suggested_array, $sub_directory);
            }
            return $suggested_array;
        }
        return $all_sub_directories;
    }
}
