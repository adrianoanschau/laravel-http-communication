<?php
    $route = route('users.index');
    $editAction = route('users.update', ['user' => 'resourceId']);
    $deleteAction = route('users.destroy', ['user' => 'resourceId']);
    $bulkDeleteAction = route('users.destroy.bulk', ['ids' => 'resourceId']);
?>

<x-datatable>
    @if(request()->user()->admin)
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
    @endif
</x-datatable>

<form id="deleteForm" method="DELETE" action="{{$deleteAction}}" async>
    <input type="hidden" id="resourceId" name="resourceId" />
</form>

<form id="bulkDeleteForm" method="DELETE" action="{{$bulkDeleteAction}}" async>
    <input type="hidden" id="resourceId" name="resourceId" />
</form>

@if(request()->user()->admin)
@push('modals')
    <x-form-modal id="createFormModal" title="Create User" action="{{route('users.store')}}" method="POST">
        @include('users.partials.user-form-fields')
    </x-form-modal>

    <x-form-modal id="editFormModal" title="Edit User" action="{{route('users.update', ['user' => 'resourceId'])}}" method="PATCH">
        <input type="hidden" id="resourceId" name="resourceId" />

        @include('users.partials.user-form-fields')
    </x-form-modal>
@endpush
@endif

@push('scripts')
<script type="module">
    const submit = (event) => {
        event.preventDefault();
    }

    (function () {
        const containerSelector = "#datatable";
        const dataTable = new DataTable(
            containerSelector,
            @json($route),
            @json($columns),
            @json(request()->user()->admin),
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

        dataTable.on('editRow', ({ resourceId, resourceData, columns }) => {

            const editForm = $(`#editFormModalForm`);
            editForm.find('#resourceId').val(resourceId)

            columns.forEach(({ field }) => {
                const input = editForm.find(`#${field}`);

                if (resourceData[field] && !!input.length) {
                    input.val(resourceData[field]);
                }
            });
        });

        dataTable.on('deleteRow', ({ resourceId }) => {

            dataTable.setLoading();
            dataTable.flagRowsForDelete([resourceId]);

            const deleteForm = $(`#deleteForm`);
            deleteForm.find('#resourceId').val(resourceId);
            deleteForm.submit();
        });

        dataTable.on('bulkDelete', ({ resourcesIds }) => {
            dataTable.setLoading();
            dataTable.flagRowsForDelete(resourcesIds);

            const deleteForm = $(`#bulkDeleteForm`);
            deleteForm.find('#resourceId').val(resourcesIds.join(';'));
            deleteForm.submit();
        })
    })();
</script>
@endpush
