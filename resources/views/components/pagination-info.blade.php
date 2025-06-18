@props(['paginator', 'itemName' => 'elementos'])

@if($paginator->hasPages())
    <div class="mb-4 text-sm text-slate-600">
        Mostrando {{ $paginator->firstItem() ?? 0 }} a {{ $paginator->lastItem() ?? 0 }} de {{ $paginator->total() }} {{ $itemName }}
    </div>
@endif 