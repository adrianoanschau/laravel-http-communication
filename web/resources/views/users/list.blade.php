<x-datatable>
    <x-slot name="actions">
        <x-button modal="#createUserFormModal">
            <x-icon class="text-white">
                <path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 12h14m-7 7V5"/>
            </x-icon>
            <span>Create a User</span>
        </x-button>

        <x-button id="bulk-delete-button" disabled color="bg-red-600">
            <x-icon class="text-white">
                <path fill-rule="evenodd" d="M8.6 2.6A2 2 0 0 1 10 2h4a2 2 0 0 1 2 2v2h3a1 1 0 1 1 0 2v12a2 2 0 0 1-2 2H7a2 2 0 0 1-2-2V8a1 1 0 0 1 0-2h3V4c0-.5.2-1 .6-1.4ZM10 6h4V4h-4v2Zm1 4a1 1 0 1 0-2 0v8a1 1 0 1 0 2 0v-8Zm4 0a1 1 0 1 0-2 0v8a1 1 0 1 0 2 0v-8Z" clip-rule="evenodd"/>
            </x-icon>
            <span>Delete Selected Rows</span>
        </x-button>
    </x-slot>

    <x-slot name="content">
        <div id="datatable" class="relative" data-te-max-width="1168"></div>
    </x-slot>
</x-datatable>

@push('modals')
    <x-form-modal id="createUserFormModal" title="Create User" action="{{ route('users.store') }}">
        @include('users.partials.user-form-fields')
    </x-form-modal>

    <x-form-modal id="editUserFormModal" title="Edit User" action="{{ route('users.update', ['user' => 'userId']) }}" method="PATCH">
        @include('users.partials.user-form-fields')
    </x-form-modal>
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
    @vite(['resources/js/list-users.js'])
@endpush
