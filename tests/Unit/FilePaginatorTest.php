<?php

namespace Tests\Unit;

use App\Helpers\FilePaginator;
use PHPUnit\Framework\TestCase;

class FilePaginatorTest extends TestCase
{
    protected $filePaginator;

    protected function setUp() :void
    {
        $this->filePaginator = new FilePaginator(12485);
    }

    public function testSetCountPerPageMethod()
    {
        $this->filePaginator->setCountPerPage(10);
        $this->assertEquals(10, $this->filePaginator->getCountPerPage());
    }

    public function testSetTotalResults()
    {
        $this->filePaginator->setTotalResults(12485);
        $this->assertEquals(12485, $this->filePaginator->getTotalResults());
    }

    public function testSetCountOfPages()
    {
        $this->filePaginator->setCountOfPages();
        $this->assertEquals(1249, $this->filePaginator->getCountOfPages());
    }

    public function testSetCurrentPage()
    {
        $this->filePaginator->setCurrentPage();
        $this->assertEquals(1, $this->filePaginator->getCurrentPage());
    }

    public function testSetFirstAndLastPages()
    {
        $this->filePaginator->setFirstPage();
        $this->filePaginator->setLastPage();
        $this->assertEquals(1, $this->filePaginator->getFirstPage());
        $this->assertEquals(1249, $this->filePaginator->getLastPage());
    }

    public function testNextAndLastPage()
    {
        $this->assertEquals(2, $this->filePaginator->getNextPage());
        $this->assertEquals(1, $this->filePaginator->getPreviousPage());
    }

    public function testIfTotalResultsIsEmpty()
    {
        $filePaginator = new FilePaginator(0);
        $this->assertEquals(0, $filePaginator->getCountOfPages());
        $this->assertEquals(0, $filePaginator->getCurrentPage());
        $this->assertEquals(0, $filePaginator->getFirstPage());
        $this->assertEquals(0, $filePaginator->getLastPage());
        $this->assertEquals(0, $filePaginator->getNextPage());
        $this->assertEquals(0, $filePaginator->getLastPage());
    }
}
