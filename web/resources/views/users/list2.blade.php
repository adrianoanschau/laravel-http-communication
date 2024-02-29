<x-async-datatable
    route="{{route('users.index')}}"
    create-action="{{route('users.store')}}"
    edit-action="{{route('users.update', ['user' => 'rowId'])}}"
    delete-action="{{route('users.destroy', ['user' => 'rowId'])}}"
    bulk-delete-action="{{route('users.destroy.bulk', ['ids' => 'rowsIds'])}}"
    :columns="$columns">
    <x-slot name="createFormFields">
        @include('users.partials.user-form-fields')
    </x-slot>

    <x-slot name="editFormFields">
        @include('users.partials.user-form-fields')
    </x-slot>
</x-async-datatable>
