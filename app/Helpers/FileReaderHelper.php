<?php


namespace App\Helpers;


/**
 * Class FileReaderHelper
 * @package App\Helpers
 */
class FileReaderHelper
{
    /**
     * @var
     */
    protected $file_path;
    /**
     * @var
     */
    protected $file_text;

    /**
     * FileReaderHelper constructor.
     * @param $file_path
     */
    public function __construct($file_path)
    {
        $this->file_path = $file_path;
    }

    /**
     * @return array|false|string
     */
    public function readFile()
    {
        $file = config("app.log_directory_path") . $this->file_path;
        if (file_exists($file))
        {
            $this->file_text = file_get_contents($file);
            return $this->file_text;
        }
        else
            return "file_not_found";
    }

    /**
     * @return array|false|string[]
     */
    public function explodeToLines()
    {
        return preg_split('/\r\n|\r|\n/', $this->file_text);
    }

    /**
     * @return int
     */
    public function getLinesCount()
    {
        return count($this->explodeToLines());
    }

    /**
     * @param $offset
     * @param $limit
     * @return array|false|string|string[]
     */
    public function getLinesToShow($offset, $limit)
    {
        if ($this->getLinesCount() > 0)
            return array_slice($this->explodeToLines(), $offset, $limit);
        else
            return "";
    }
}
