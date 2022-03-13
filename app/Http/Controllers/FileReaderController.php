<?php

namespace App\Http\Controllers;

use App\Helpers\DirectoryAutoCompleteHelper;
use App\Helpers\FilePaginator;
use App\Helpers\FileReaderHelper;
use App\Traits\HttpResponseStatus;
use App\Traits\Upload;
use Illuminate\Http\Request;

/**
 * Class FileReaderController
 * @package App\Http\Controllers
 */
class FileReaderController extends Controller
{
    use HttpResponseStatus, Upload;

    /**
     * @var
     */
    public $filePaginator;

    /**
     * @return \Illuminate\Contracts\Foundation\Application|\Illuminate\Contracts\View\Factory|\Illuminate\Contracts\View\View
     */
    public function index()
    {
        return view('lines');
    }

    /**
     * @return \Illuminate\Config\Repository|\Illuminate\Contracts\Foundation\Application|mixed
     */
    public function getLogDirectory()
    {
        return config("app.log_directory_path");
    }

    /**
     * @param Request $request
     * @return \Illuminate\Contracts\Foundation\Application|\Illuminate\Contracts\Routing\ResponseFactory|\Illuminate\Http\JsonResponse|\Illuminate\Http\Response
     */
    public function readFile(Request $request)
    {
        $request->validate([
            "file_path" => "required|max:100"
        ]);

        $file_reader = new FileReaderHelper($request->file_path);
        $file = $file_reader->readFile();
        if ($file == "file_not_found")
            return $this->failureResponse(402, "Invalid File");

        $file_paginator = new FilePaginator($file_reader->getLinesCount());

        $data = array();
        $data['total']          = $file_paginator->getTotalResults();
        $data['pages_count']    = $file_paginator->getCountOfPages();
        $data['per_page']       = $file_paginator->getCountPerPage();
        $data['first_page']     = $file_paginator->getFirstPage();
        $data['previous_page']  = $file_paginator->getPreviousPage();
        $data['current_page']   = $file_paginator->getCurrentPage();
        $data['next_page']      = $file_paginator->getNextPage();
        $data['last_page']      = $file_paginator->getLastPage();
        $data['from']           = $file_paginator->getStartIndex() + 1;
        $data['to']             = $file_paginator->getStartIndex() + $file_paginator->getCountPerPage();
        $data['lines']          = $file_reader->getLinesToShow($file_paginator->getStartIndex(), $file_paginator->getCountPerPage());

        return response()->json($data);
    }
}
