<?php
class Pagination
{
    private $totalItems; // Tổng số phần từ
    private $totalItemsPerPage; // // Tổng số phần tử trên mỗi trang
    private $pageRange; // số ô vuông sẽ xuất hiện ở pagination - số trang xuất hiện
    private $totalPages;
    private $currentPage = 1;

    public function __construct($totalItems, $totalItemsPerPage = 1, $pageRange = 3)
    {
        $this->totalItems = $totalItems;
        $this->totalItemsPerPage = $totalItemsPerPage;
        $this->pageRange = $pageRange;
        $this->currentPage = $this->getCurrentPage();
        $this->totalPages = ceil($this->totalItems / $this->totalItemsPerPage);
        $this->lastPage();
    }

    public function limit()
    {
        return ($this->currentPage - 1) * $this->totalItemsPerPage;
    }

    private function getCurrentPage()
    {
        if (isset($_GET['page'])) {
            $this->currentPage = preg_replace('#[^0-9]#', '', $_GET['page']);
            $this->currentPage = ($this->currentPage == 0) ? 1 : $this->currentPage; // Đảm bảo giá trị không là 0
            $this->currentPage = max(1, $this->currentPage); // Đảm bảo giá trị là số nguyên dương
        }
        return $this->currentPage;
    }

    // always get last page
    private function lastPage()
    {
        if ($this->currentPage > $this->totalPages) {
            $this->currentPage = $this->totalPages;
        }
    }

    public function showPagination()
    {
        $paginationHTML = '';
        if ($this->totalPages > 1) {
            $pageLinks = '';

            // Render clickable left
            for ($i = $this->currentPage - $this->pageRange; $i < $this->currentPage; $i++) {
                if ($i > 0) {
                    $pageLinks .= "<li class='page-item'><a class='page-link' href='{$_SERVER['PHP_SELF']}?page=$i'>$i</a></li>";
                }
            }

            // Render target page number without link
            $pageLinks .= "<li class='page-item active'><a class='page-link'>$i</a></li>";

            // Render clickable right
            for ($i = $this->currentPage + 1; $i <= $this->totalPages; $i++) {
                $pageLinks .= "<li class='page-item'><a class='page-link' href='{$_SERVER['PHP_SELF']}?page=$i'>$i</a></li>";
                if ($i >= $this->currentPage + $this->pageRange) {
                    break;
                }
            }


            $next = "<a class='page-link'>Next</a>";
            $end = "<a class='page-link'>End</a>";
            if ($this->currentPage < $this->totalPages) {
                $numNext = $this->currentPage + 1;
                $next = "<a class='page-link' href='{$_SERVER['PHP_SELF']}?page=$numNext'>Next</a>";
                $end = "<a class='page-link' href='{$_SERVER['PHP_SELF']}?page=$this->totalPages'>End</a>";
            }

            $start = "<a class='page-link'>Start</a>";
            $previous = "<a class='page-link'>previous</a>";
            if ($this->currentPage > 1) {
                $pre = $this->currentPage - 1;
                $previous = "<a class='page-link' href='{$_SERVER['PHP_SELF']}?page=$pre'>previous</a>";
                $start = "<a class='page-link' href='{$_SERVER['PHP_SELF']}?page=1'>Start</a>";
            }

            $paginationHTML .= "<ul class='pagination'>
                            <li class='page-item'>$start</li>
                            <li class='page-item'>$previous</li>
                            $pageLinks
                            <li class='page-item'>$next</li>
                            <li class='page-item'>$end</li>
                        </ul>";
        }
        return $paginationHTML;
    }
}
