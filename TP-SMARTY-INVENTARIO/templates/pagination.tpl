<div class="pagination">
    {if $currentPage > 1}
        <a href="{$baseURL}?page={$currentPage - 1}" class="pagination-link">Anterior</a>
    {/if}

    {section name=page_loop start=1 loop=$totalPages+1}
        {if $smarty.section.page_loop.index == $currentPage}
            <span class="pagination-current">{$smarty.section.page_loop.index}</span>
        {else}
            <a href="{$baseURL}?page={$smarty.section.page_loop.index}" class="pagination-link">{$smarty.section.page_loop.index}</a>
        {/if}
    {/section}

    {if $currentPage < $totalPages}
        <a href="{$baseURL}?page={$currentPage + 1}" class="pagination-link">Siguiente</a>
    {/if}
</div>