<?php
class Pagination
{

    public function getTotalPage($total, $limit)
    {
        $total = $total / $limit;
        $total = ceil($total);
        return $total;
    }

    public function getCurrentPage($total_page)
    {
        $page = 1;
        if (isset($_GET['page']) && is_numeric($_GET['page'])) {
            $page = $_GET['page'];
            if ($page >= $total_page) {
                $page = $total_page;
            }
        }
        return $page;
    }

    public function getPrevPage($total_page)
    {
        $prev_page = '';
        $current_page = $this->getCurrentPage($total_page);
        if ($current_page >= 2) {
            $page = $current_page - 1;
            $prev_url = $this->getPageUrl($page);
            $prev_page = "<li class='page-item'>
                        <a class='page-link' href='$prev_url' aria-label='Previous'>
                            <span aria-hidden='true'>&laquo;</span>
                            <span class='sr-only'>Previous</span>
                        </a>
                    </li>";
        } else {
            $prev_page = "<li class='page-item disabled'>
                        <a class='page-link' href='#' aria-label='Previous'>
                            <span aria-hidden='true'>&laquo;</span>
                            <span class='sr-only'>Previous</span>
                        </a>
                    </li>";
        }
        return $prev_page;
    }

    public function getNextPage($total_page)
    {
        $next_page = '';
        $current_page = $this->getCurrentPage($total_page);
        if ($current_page < $total_page) {
            $page = $current_page + 1;
            $next_url = $this->getPageUrl($page);
            $next_page = "<li class='page-item'>
                        <a class='page-link' href='$next_url' aria-label='Next'>
                            <span aria-hidden='true'>&raquo;</span>
                            <span class='sr-only'>Next</span>
                        </a>
                    </li>";
        } else {
            $next_page = "<li class='page-item disabled'>
                        <a class='page-link' href='#' aria-label='Next'>
                            <span aria-hidden='true'>&raquo;</span>
                            <span class='sr-only'>Next</span>
                        </a>
                    </li>";
        }
        return $next_page;
    }

    public function getPagination($current_page, $total_record, $limit)
    {
        $data = '<ul class="pagination pagination-sm justify-content-center mb-0">';
        $total_page = $this->getTotalPage($total_record, $limit);
        if ($total_page == 1) {
            return '';
        }
        $prev_link = $this->getPrevPage($total_page);
        $data .= $prev_link;
        for ($page = 1; $page <= $total_page; $page++) {
            $current_page = $this->getCurrentPage($total_page);
            if ($current_page == $page) {
                $data .= "<li class='page-item active'><a class='page-link' href='#'>$page</a></li>";
            } else {
                $page_url = $this->getPageUrl($page);
                $data .= "<li class='page-item'><a class='page-link' href='$page_url'>$page</a></li>";
            }
        }
        $next_link = $this->getNextPage($total_page);
        $data .= $next_link;
        $data >= '</ul>';
        return $data;
    }

    public function getPageUrl($page)
    {
        $page_url = '';
        $current_url = $_SERVER['REQUEST_URI'];
        if (!isset($_GET['page'])) {
            if (preg_match('/\?$/', $current_url) != 0) {
                $page_url = $current_url . "page=" . $page;
            } else if (preg_match('/\?\w+\=.*/', $current_url) == 0) {
                $page_url = $current_url . "?page=" . $page;
            } else {
                $page_url = $current_url . "&page=" . $page;
            }
        } else {
            $page_url = preg_replace('/page=(\d+)/', 'page=' . $page, $current_url);
        }
        return $page_url;
    }

    public function getPrevPageWeb($total_page)
    {
        $prev_page = '';
        $current_page = $this->getCurrentPage($total_page);
        if ($current_page >= 2) {
            $page = $current_page - 1;
            $prev_url = $this->getPageUrl($page);
            $prev_page = "
          <li>
              <a rel='prev' href='$prev_url' class='previous disabled js-search-link'>
                  Previous
              </a>
          </li>
        ";
        } else {
        }
        return $prev_page;
    }

    public function getNextPageWeb($total_page)
    {
        $next_page = '';
        $current_page = $this->getCurrentPage($total_page);
        if ($current_page < $total_page) {
            $page = $current_page + 1;
            $next_url = $this->getPageUrl($page);
            $next_page = "
        <li>
            <a rel='next' href='$next_url' class='next disabled js-search-link'>
                Next
            </a>
        </li>
      ";

        } else {
        }
        return $next_page;
    }

    public function getPaginationWeb($current_page, $total_record, $limit)
    {
        $data = '<ul>';
        $total_page = $this->getTotalPage($total_record, $limit);
        if ($total_page == 1) {
            return '';
        }
        $prev_link = $this->getPrevPageWeb($total_page);
        $data .= $prev_link;
        for ($page = 1; $page <= $total_page; $page++) {
            $current_page = $this->getCurrentPage($total_page);
            if ($current_page == $page) {
                $data .= "
          <li class='current active'>
              <a rel='nofollow' href='#' class='disabled js-search-link'>
                $page
              </a>
          </li>
        ";
            } else {
                $page_url = $this->getPageUrl($page);
                $data .= "
          <li>
              <a rel='nofollow' href='$page_url' class='disabled js-search-link'>
                  $page
              </a>
          </li>
        ";
            }
        }
        $next_link = $this->getNextPageWeb($total_page);
        $data .= $next_link;
        $data .= '</ul>';
        return $data;
    }
}
