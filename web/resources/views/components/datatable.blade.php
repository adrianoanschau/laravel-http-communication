<div class="flex flex-col">
    <div class="bulk-actions flex justify-end mb-2 h-10">
        <div class="flex">
            @if(isset($actions))
                {{$actions}}
            @endif
        </div>
    </div>

    <div class="overflow-x-auto sm:-mx-6 lg:-mx-8">
        <div class="inline-block min-w-full py-2 sm:px-6 lg:px-8">
            <div class="overflow-hidden relative">
                <div id="datatable" class="relative" data-te-max-width="1168"></div>
                <div class="disable-table absolute top-0 w-full h-full bg-gray-100 opacity-50 hidden" style="z-index: 100;"></div>
            </div>
        </div>
    </div>
</div>

@push('styles')
    <style>
        #datatable table thead th:nth-child(2),
        #datatable table tbody td:nth-child(2) {
            display: none;
        }
        #datatable table thead th:last-child,
        #datatable table tbody td:last-child {
            text-align: right;
        }
        #datatable table tr.active td {
            background-color: rgb(245, 245, 245) !important;
        }

        #datatable.loading button,
        #datatable.loading input {
            box-shadow: none !important;
            opacity: 0.5 !important;
        }

        button[disabled] {
            box-shadow: none !important;
            opacity: 0.5 !important;
        }

        .row-deleted {
            text-decoration: line-through;
            color: lightgray !important;
        }

        .progress-bar-container, .loading-message {
            position: absolute;
            top: 0;
            z-index: 100;
        }
    </style>
@endpush
