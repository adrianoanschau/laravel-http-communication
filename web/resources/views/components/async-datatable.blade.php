@props([
    'route',
    'columns',
    'createAction',
    'createActionLabel' => 'Create',
    'editAction',
    'editActionLabel' => 'Edit',
    'deleteAction',
    'bulkDeleteAction',
    'bulkDeleteActionLabel' => 'Delete Selected Rows',
])

<div class="flex flex-col">
    <div class="bulk-actions flex justify-end mb-2 h-10">
        <div class="flex">
            @if(isset($createAction) && isset($createFormFields))
            <x-button modal="#createFormModal">
                <x-icon class="text-white">
                    <path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 12h14m-7 7V5"/>
                </x-icon>
                <span>{{$createActionLabel}}</span>
            </x-button>
            @endif

            @if(isset($bulkDeleteAction))
            <x-button id="bulk-delete-button" disabled color="bg-red-600">
                <x-icon class="text-white">
                    <path fill-rule="evenodd" d="M8.6 2.6A2 2 0 0 1 10 2h4a2 2 0 0 1 2 2v2h3a1 1 0 1 1 0 2v12a2 2 0 0 1-2 2H7a2 2 0 0 1-2-2V8a1 1 0 0 1 0-2h3V4c0-.5.2-1 .6-1.4ZM10 6h4V4h-4v2Zm1 4a1 1 0 1 0-2 0v8a1 1 0 1 0 2 0v-8Zm4 0a1 1 0 1 0-2 0v8a1 1 0 1 0 2 0v-8Z" clip-rule="evenodd"/>
                </x-icon>
                <span>{{$bulkDeleteActionLabel}}</span>
            </x-button>
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

<form id="bulkDeleteForm" method="DELETE" action="{{$bulkDeleteAction}}"></form>


@push('modals')
    @if(isset($createAction) && isset($createFormFields))
    <x-form-modal id="createFormModal" title="{{ $createActionLabel }}" action="{{$createAction}}" method="POST">
        {{$createFormFields}}
    </x-form-modal>
    @endif

    @if(isset($editAction) && isset($editFormFields))
    <x-form-modal id="editFormModal" title="{{ $editActionLabel }}" action="{{$editAction}}" method="PATCH">
        {{$editFormFields}}
    </x-form-modal>
    @endif
@endpush

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

@push('scripts')
    <script type="module">
        (function () {
            const containerSelector = "#datatable";
            const dataTable = new DataTable(
                containerSelector,
                @json($route),
                @json($columns),
                "#editFormModal",
                '#bulk-delete-button'
            );

            dataTable.load();

            const container = $(containerSelector);
            const disableTable = $(".disable-table");

            dataTable.on('loading', (loading) => {
                if (loading) {
                    container.addClass("loading");
                    disableTable.removeClass("hidden");
                } else {
                    container.removeClass("loading");
                    disableTable.addClass("hidden");
                }
            });


            function onSubmitUpdateData(form, rowId = null, previousData = null) {
                return (event) => {
                    event.preventDefault();

                    const data = Object.fromEntries(new FormData(form.get(0)));

                    if (previousData) {
                        Object.entries(previousData).forEach(([key, value]) => {
                            if (data[key] === value) {
                                delete data[key];
                            }
                        });
                    }

                    if (!Object.keys(data).length) return;

                    axios
                        .request({
                            url: form.attr("action").replace("rowId", rowId),
                            method: form.attr("method"),
                            data,
                        })
                        .then(({ data }) => {
                            window.location.reload();
                        });
                };
            }

            const editForm = $(`#editFormModalForm`);

            const onEditFormSubmit = ({ rowId, rowData, columns }) => {
                columns.forEach(({ field }) => {
                    const input = editForm.find(`#${field}`);

                    if (rowData[field] && !!input.length) {
                        input.val(rowData[field]);
                    }
                });

                return onSubmitUpdateData(editForm, rowId, rowData);
            };

            let editFormSubmitCallback = () => {};

            dataTable.on('editRow', ({ rowId, rowData, columns }) => {
                const editAction = @json($editAction);

                editForm.off("submit", editFormSubmitCallback);
                editFormSubmitCallback = onEditFormSubmit({ rowId, rowData, columns });
                editForm.on("submit", editFormSubmitCallback);
            });

            dataTable.on('deleteRow', ({ rowId }) => {
                const deleteAction = @json($deleteAction);

                dataTable.setLoading();
                dataTable.flagRowsForDelete([rowId]);

                axios.delete(deleteAction.replace('rowId', rowId)).then(() => {
                    dataTable.load(false);
                });
            });

            dataTable.on('bulkDelete', ({ rowsIds }) => {
                const bulkDeleteAction = @json($bulkDeleteAction);

                dataTable.setLoading();
                dataTable.flagRowsForDelete(rowsIds);

                axios.delete(bulkDeleteAction.replace('rowsIds', rowsIds.join(';'))).then(() => {
                    dataTable.load(false);
                });
            })
        })();
    </script>
@endpush
