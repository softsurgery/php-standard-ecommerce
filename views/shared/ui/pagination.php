<?php
function renderPagination($currentPage, $pageSize, $totalItems, $id = 'pagination')
{
    $pageCount = (int) ceil($totalItems / $pageSize);
    $start = ($totalItems === 0) ? 0 : (($currentPage - 1) * $pageSize + 1);
    $end = min($currentPage * $pageSize, $totalItems);
    $hasPrev = $currentPage > 1;
    $hasNext = $currentPage < $pageCount;

    // Compute prev/next page numbers
    $prevPage = $currentPage - 1;
    $nextPage = $currentPage + 1;

    // Compute disabled attributes
    $prevDisabled = $hasPrev ? '' : 'disabled';
    $nextDisabled = $hasNext ? '' : 'disabled';

    echo <<<HTML
<div id="{$id}" class="flex flex-row justify-between items-center mt-4">
  <span class="text-sm">
      Showing <span class="font-semibold">{$start}</span> to <span class="font-semibold">{$end}</span> of <span class="font-semibold">{$totalItems}</span> Entries
  </span>
  <div class="inline-flex mt-2 xs:mt-0">
    <button class="flex items-center justify-center px-4 h-10 text-base font-medium text-white bg-gray-800 rounded-s hover:bg-gray-900 dark:bg-gray-800 dark:border-gray-700 dark:text-gray-400 dark:hover:bg-gray-700 dark:hover:text-white" 
        data-page="{$prevPage}" {$prevDisabled}>
        <svg class="w-3.5 h-3.5 me-2 rtl:rotate-180" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 14 10">
          <path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 5H1m0 0 4 4M1 5l4-4"/>
        </svg>
        Prev
    </button>
    <button class="flex items-center justify-center px-4 h-10 text-base font-medium text-white bg-gray-800 border-0 border-s border-gray-700 rounded-e hover:bg-gray-900 dark:bg-gray-800 dark:border-gray-700 dark:text-gray-400 dark:hover:bg-gray-700 dark:hover:text-white"
        data-page="{$nextPage}" {$nextDisabled}>
        Next
        <svg class="w-3.5 h-3.5 ms-2 rtl:rotate-180" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 14 10">
          <path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M1 5h12m0 0L9 1m4 4L9 9"/>
        </svg>
    </button>
  </div>
</div>
HTML;
}
?>