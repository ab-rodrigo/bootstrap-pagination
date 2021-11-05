<?php

/**
 * Pagination Bootstrap
 *
 * Author: Rodrigo Barbosa
 * Email: ab.rodrigo@outlook.com
 * GitHub: https://github.com/ab-rodrigo/bootstrap-pagination
 *
 * @param int $total_records
 * @param int $records_per_pages
 * @param int $current_page
 * @param int $offset
 * @param string $str_link
 *
 * @return array
 *
 */

final class Pagination
{
    private $total_records     = null;
    private $records_per_pages = null;
    private $pages             = null;
    private $total_pages       = null;
    private $current_page      = null;
    private $offset            = null;
    private $str_link          = null;
    private $str_previous      = null;
    private $str_next          = null;
    private $return            = array();
    private $error             = null;
    private $size              = "pagination-sm";
    private $alignment         = "justify-content-center";
    private $icon              = true;
    private $stats             = true;

    public function create($total_records, $records_per_pages, $current_page, $offset, $str_link = "")
    {
        try {
            if (isset($total_records) && is_int($total_records) && $total_records > 0) {
                $this->total_records           = $total_records;
                $this->return['total_records'] = $this->total_records;
            } else {
                throw new \Exception('Invalid parameter: Total records must be positive int.');
            }

            if (isset($records_per_pages) && is_int($records_per_pages) && $records_per_pages > 0) {
                $this->records_per_pages           = $records_per_pages;
                $this->return['records_per_pages'] = $this->records_per_pages;
        } else {
            throw new \Exception('Invalid parameter: Records per page must be positive int.');
        }

            $this->offset = $offset <= 1 ? 2 : $offset + 1;

            if (isset($str_link) && is_string($str_link) && $str_link != "") {
                $this->str_link           = $str_link;
                $this->return['str_link'] = $this->str_link;
            } else {
                throw new \Exception('Invalid parameter: String link invalid.');
            }
        } catch (\Exception $e) {
            echo $this->error = $e->getMessage();
        }

        if (!$this->error) {
            $this->str_previous = $this->icon ? '<span aria-hidden="true">&laquo;</span>' : 'Previous';
            $this->str_next     = $this->icon ? '<span aria-hidden="true">&raquo;</span>' : 'Next';

            $this->total_pages           = intval(ceil($this->total_records / $this->records_per_pages));
            $this->return['total_pages'] = $this->total_pages;

            if ($current_page < 1) {
                $this->current_page = 1;
            } elseif ($current_page >= $this->total_pages) {
                $this->current_page = $this->total_pages;
            } else {
                $this->current_page = $current_page;
            }

            $this->return['current_page']  = $this->current_page;
            $this->return['offset']        = $this->offset;
            $this->return['stats']['start'] = (($this->records_per_pages * $this->current_page) - $this->records_per_pages) + 1;
            $this->return['stats']['end']   = $this->return['stats']['start'] + $this->records_per_pages - 1 < $this->total_records ? $this->return['stats']['start'] + $this->records_per_pages - 1 : $this->total_records;
            $this->return['stats']['total'] = $this->total_records;

            $this->return['output'] = "<nav id='pagination' class='text-center' aria-label='Page navigation'>";

            if ($this->stats) {
                $this->return['output'] .= "<div><span>{$this->return['stats']['start']} to {$this->return['stats']['end']} of {$this->return['stats']['total']}</span></div>";
            }
                    
            $this->return['output'] .= "<ul class='pagination {$this->size} {$this->alignment}'>";

            if ($this->current_page > 1) {
                $link = $this->str_link . ($this->current_page - 1);
                $this->return['output'] .= "<li class='page-item' title='Previous page'><a class='page-link' href='{$link}' aria-label='Previous'>{$this->str_previous}</a></li>";
            } else {
                $this->return['output'] .= "<li class='page-item disabled' title='Previous page'><a class='page-link' aria-label='Previous' tabindex='-1' aria-disabled='true'>{$this->str_previous}</a></li>";
            }

            if ($this->current_page == 1) {
                $this->return['output'] .= "<li class='page-item active' title='Page 1' aria-current='page'><a class='page-link'>1</a></li>";
            } else {
                $link = $this->str_link . 1;
                $this->return['output'] .= "<li class='page-item' title='Page 1'><a class='page-link' href='{$link}'>1</a></li>";
            }

            if ($this->total_pages < 6) {
                for ($page = 2; $page < $this->total_pages; $page++) {
                    $link = $this->str_link . $page;
                    if ($this->current_page == $page) {
                        $this->return['output'] .= "<li class='page-item active' disabled title='Page {$page}'><span class='page-link'>{$page}</span></li>";
                    } else {
                        $this->return['output'] .= "<li class='page-item' title='Page {$page}'><a class='page-link' href='{$link}'>{$page}</a></li>";
                    }
                }
            } else {
                $offset_range = ($this->offset - 1) / 2;

                $start_range = $this->current_page - $this->offset;
                if ($start_range < 2) {
                    $start_range = 2;
                }

                $end_range = $this->current_page + $this->offset;
                if ($end_range > $this->total_pages - 1) {
                    $end_range = $this->total_pages - 1;
                }

                $pages = range($start_range, $end_range);

                $pages_range = [];
                foreach ($pages as $key => $page) {
                    $page = intval($page);
                    if ($this->current_page == $page) {
                        $pages_range[] = "<li class='page-item active' title='Page {$page}' aria-current='page'><span class='page-link'>{$page}</span></li>";
                    } else {
                        $link          = $this->str_link . $page;
                        $pages_range[] = "<li class='page-item' title='Page {$page}'><a class='page-link' href='{$link}'>{$page}</a></li>";
                    }
                }

                if ($start_range > 2) {
                    $key_first               = array_key_first($pages_range);
                    $pages_range[$key_first] = "<li class='page-item disabled'><span class='page-link'>...</span></li>";
                }
                if ($this->total_pages - 2 >= $end_range) {
                    $key_last               = array_key_last($pages_range);
                    $pages_range[$key_last] = "<li class='page-item disabled'><span class='page-link'>...</span></li>";
                }

                $this->return['output'] .= implode($pages_range);
            }

            if ($this->total_records > $this->records_per_pages) {
                $link = $this->str_link . $this->total_pages;
                if ($this->current_page == $this->total_pages) {
                    $this->return['output'] .= "<li class='page-item active' title='Page {$this->total_pages}'><a class='page-link' aria-label='Next'>{$this->total_pages}</a></li>";
                } else {
                    $this->return['output'] .= "<li class='page-item' title='Page {$this->total_pages}'><a class='page-link' href='{$link}'>{$this->total_pages}</a></li>";
                }
            }

            if ($this->current_page < $this->total_pages) {
                $link = $this->str_link . (int) ($this->current_page + 1);
                $this->return['output'] .= "<li class='page-item' title='Next page'><a class='page-link' href='{$link}' aria-label='Next'>{$this->str_next}</a></li>";
            } else {
                $this->return['output'] .= "<li class='page-item disabled' title='Next page'><a class='page-link' aria-label='Next' tabindex='-1' aria-disabled='true'>{$this->str_next}</a></li>";
            }

            $this->return['output'] .= '</ul></nav>';

            $this->return['status'] = true;
        } else {
            $this->return['status'] = false;
            $this->return['error']  = $this->error;
        }

        return $this->return;
    }
}
