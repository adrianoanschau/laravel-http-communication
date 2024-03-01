<?php
    $route = route('users.index');
    $editAction = route('users.update', ['user' => 'rowId']);
    $deleteAction = route('users.destroy', ['user' => 'rowId']);
    $bulkDeleteAction = route('users.destroy.bulk', ['ids' => 'rowsIds']);
?>

<x-datatable>
    <x-slot:actions>
        <x-button modal="#createFormModal">
            <x-icon class="text-white">
                <path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 12h14m-7 7V5"/>
            </x-icon>
            <span>Create User</span>
        </x-button>

        <x-button id="bulk-delete-button" disabled color="bg-red-600">
            <x-icon class="text-white">
                <path fill-rule="evenodd" d="M8.6 2.6A2 2 0 0 1 10 2h4a2 2 0 0 1 2 2v2h3a1 1 0 1 1 0 2v12a2 2 0 0 1-2 2H7a2 2 0 0 1-2-2V8a1 1 0 0 1 0-2h3V4c0-.5.2-1 .6-1.4ZM10 6h4V4h-4v2Zm1 4a1 1 0 1 0-2 0v8a1 1 0 1 0 2 0v-8Zm4 0a1 1 0 1 0-2 0v8a1 1 0 1 0 2 0v-8Z" clip-rule="evenodd"/>
            </x-icon>
            <span>Delete Selected Rows</span>
        </x-button>
    </x-slot:actions>
</x-datatable>

<form id="bulkDeleteForm" method="DELETE" action="{{$bulkDeleteAction}}"></form>

@push('modals')
    <x-form-modal id="createFormModal" title="Create User" action="{{route('users.store')}}" method="POST">
        @include('users.partials.user-form-fields')
    </x-form-modal>

    <x-form-modal id="editFormModal" title="Edit User" action="{{route('users.update', ['user' => 'rowId'])}}" method="PATCH">
        @include('users.partials.user-form-fields')
    </x-form-modal>
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
