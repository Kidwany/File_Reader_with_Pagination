<?php


namespace App\Helpers;


/**
 * Class FilePaginator
 * @package App\Helpers
 */
class FilePaginator
{
    /**
     * @var int
     */
    protected $count_per_page;
    /**
     * @var int
     */
    protected $current_page;
    /**
     * @var
     */
    protected $count_of_pages;
    /**
     * @var
     */
    protected $first_page;
    /**
     * @var
     */
    protected $last_page;
    /**
     * @var
     */
    protected $total_results;

    /**
     * FilePaginator constructor.
     * @param $total_results
     */
    public function __construct($total_results)
    {
        $this->setCountPerPage();
        $this->setTotalResults($total_results);
        $this->setCountOfPages();
        $this->setCurrentPage();
        $this->setFirstPage();
        $this->setLastPage();
    }

    /**
     * @return int
     */
    public function getCountPerPage(): int
    {
        return $this->count_per_page;
    }

    public function setCountPerPage($count = null): void
    {
        if (isset($_GET['limit']) && in_array($_GET['limit'], [10, 25, 50, 100]))
            $this->count_per_page = $_GET['limit'];
        else
            $this->count_per_page = 10;
    }

    /**
     * @return int
     */
    public function getCurrentPage(): int
    {
        return $this->current_page;
    }

    public function setCurrentPage()
    {
        if (isset($_GET['page']) && $_GET['page'] <= $this->getCountOfPages())
            $this->current_page = $_GET['page'];
        elseif (isset($_GET['page']) && $_GET['page'] > $this->getCountOfPages())
            $this->current_page = $this->getCountOfPages();
        elseif (!isset($_GET['page']) && $this->getTotalResults() > 0)
            $this->current_page = 1;
        else
            $this->current_page = 0;
    }

    /**
     * @return mixed
     */
    public function getCountOfPages()
    {
        return $this->count_of_pages;
    }

    public function setCountOfPages(): void
    {
        if ($this->getTotalResults() > 0)
            $count_of_pages = ceil($this->getTotalResults() / $this->getCountPerPage());
        else
            $count_of_pages = 0;
        $this->count_of_pages = $count_of_pages;
    }

    /**
     * @return mixed
     */
    public function getTotalResults()
    {
        return $this->total_results;
    }

    /**
     * @param mixed $total_results
     */
    public function setTotalResults($total_results): void
    {
        $this->total_results = $total_results;
    }

    /**
     * @return mixed
     */
    public function getFirstPage()
    {
        return $this->first_page;
    }

    public function setFirstPage(): void
    {
        if ($this->getTotalResults() > 0)
            $this->first_page = 1;
        else
            $this->first_page = 0;
    }

    /**
     * @return mixed
     */
    public function getLastPage()
    {
        return $this->last_page;
    }

    public function setLastPage(): void
    {
        $this->last_page = $this->getCountOfPages();
    }

    /**
     * @return float|int
     */
    public function getStartIndex()
    {
        return (($this->getCurrentPage() - 1) * $this->getCountPerPage());
    }

    /**
     * @return int
     */
    public function getNextPage()
    {
        return $this->getCurrentPage() + 1 > $this->getLastPage() ? $this->getLastPage() : $this->getCurrentPage() + 1;
    }

    /**
     * @return int
     */
    public function getPreviousPage()
    {
        return $this->getCurrentPage() - 1 <= 0 ? 1 : $this->getCurrentPage() - 1;
    }

}
