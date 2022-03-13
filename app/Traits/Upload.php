<?php


namespace App\Traits;


use App\Helpers\RandomString;

/**
 * Trait Upload
 * @package App\Traits
 */
trait Upload
{

    /**
     * @var string
     */
    protected $application_folder = "uploads/";


    /**
     * @param $file_request
     * @param string $upload_path
     * @param bool $is_multiple
     * @return array|string
     * @throws \Exception
     */
    public function uploadImage($file_request, string $upload_path, bool $is_multiple = false)
    {
        $hashGenerator = RandomString::generate(10) . time() . bin2hex(random_bytes(8));
        if ($is_multiple == true)
        {
            $images_array = [];
            foreach ($file_request as $key => $file)
            {
                $image_name = $hashGenerator . $key . str_replace(" ", "", $file->getClientOriginalName());
                $image_path = public_path($this->application_folder . $upload_path);
                $file->move($image_path, $image_name);
                $images_array[] = $this->application_folder . $upload_path . '/' . $image_name;
            }
            return $images_array;
        }
        $image_name = $hashGenerator . str_replace(" ", "", $file_request->getClientOriginalName());;
        $file_request->move($this->application_folder . $upload_path, $image_name);
        return $this->application_folder . $upload_path . '/' . $image_name;
    }
}
