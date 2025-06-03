@props([
    'tableId' => 'reportsTable',
    'columns' => [],
    'data' => [],
    'title' => 'Reports',
    'searchFields' => '',
    'exportFilename' => 'report'
])

<div class="card">
    <div class="card-header">
        <h4 class="card-title">{{ $title }}</h4>
    </div>
    <div class="card-body">
        <div class="table-responsive">
            <table id="{{ $tableId }}" class="table table-hover table-bordered align-middle datatable">
                <thead class="thead-light">
                    <tr>
                        @foreach($columns as $column)
                            <th>{{ $column['title'] }}</th>
                        @endforeach
                    </tr>
                </thead>
                <tbody>
                    {{ $slot }}
                </tbody>
            </table>
        </div>
    </div>
</div>

@push('scripts')
<script>
    window.dataTableConfigs = window.dataTableConfigs || {};
    window.dataTableConfigs['{{ $tableId }}'] = {
        order: [[0, 'desc']],
        columnDefs: [
            @foreach($columns as $index => $column)
                @if(isset($column['orderable']) && !$column['orderable'])
                {
                    targets: [{{ $index }}],
                    orderable: false,
                    @if(isset($column['searchable']) && !$column['searchable'])
                    searchable: false
                    @endif
                },
                @endif
                @if(isset($column['className']))
                {
                    targets: [{{ $index }}],
                    className: '{{ $column['className'] }}'
                },
                @endif
            @endforeach
        ],
        buttons: [
            {
                extend: 'copy',
                text: '<i class="fa fa-copy"></i> Copy',
                className: 'btn btn-secondary btn-sm'
            },
            {
                extend: 'csv',
                text: '<i class="fa fa-file-csv"></i> CSV',
                className: 'btn btn-success btn-sm',
                filename: '{{ $exportFilename }}_' + new Date().toISOString().slice(0,10)
            },
            {
                extend: 'excel',
                text: '<i class="fa fa-file-excel"></i> Excel',
                className: 'btn btn-success btn-sm',
                filename: '{{ $exportFilename }}_' + new Date().toISOString().slice(0,10)
            },
            {
                extend: 'pdf',
                text: '<i class="fa fa-file-pdf"></i> PDF',
                className: 'btn btn-danger btn-sm',
                orientation: 'landscape',
                filename: '{{ $exportFilename }}_' + new Date().toISOString().slice(0,10),
                title: '{{ $title }}'
            },
            {
                extend: 'print',
                text: '<i class="fa fa-print"></i> Print',
                className: 'btn btn-info btn-sm',
                title: '{{ $title }}'
            }
        ],
        language: {
            search: "Search {{ $searchFields ? '(' . $searchFields . ')' : '' }}:",
        }
    };
</script>
@endpush
