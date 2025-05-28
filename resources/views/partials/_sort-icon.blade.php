@props(['field'])

{{-- Ensure $sortField and $sortDirection are available from the parent Livewire component --}}
@if (isset($sortField) && $sortField === $field)
    @if (isset($sortDirection) && $sortDirection === 'asc')
        <i class="fas fa-sort-up"></i>
    @else
        <i class="fas fa-sort-down"></i>
    @endif
@else
    <i class="fas fa-sort text-muted"></i> {{-- text-muted for unsorted state --}}
@endif
