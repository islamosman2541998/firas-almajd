@props(['rows'])
@if ($rows->total())
    <div class="a-table-foot">
        <span>{{ __('admin.common.showing', ['from' => $rows->firstItem(), 'to' => $rows->lastItem(), 'total' => $rows->total()]) }}</span>
        {{ $rows->onEachSide(1)->links() }}
    </div>
@endif
