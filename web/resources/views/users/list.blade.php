<div class="flex flex-col">
    <div class="overflow-x-auto sm:-mx-6 lg:-mx-8">
        <div class="inline-block min-w-full py-2 sm:px-6 lg:px-8">
            <div class="overflow-hidden">
                <div class="bulk-actions flex justify-end mb-2 h-10">
                    <div class="flex">
                        <button
                            type="button"
                            data-te-toggle="modal"
                            data-te-target="#createUserFormModal"
                            data-te-ripple-init
                            data-te-ripple-color="light"
                            class="flex items-center rounded bg-primary px-4 py-2 mr-2 text-xs font-medium uppercase leading-normal text-white shadow-[0_4px_9px_-4px_#3b71ca] transition duration-150 ease-in-out hover:bg-primary hover:shadow-[0_8px_9px_-4px_rgba(59,113,202,0.3),0_4px_18px_0_rgba(59,113,202,0.2)] focus:bg-primary focus:shadow-[0_8px_9px_-4px_rgba(59,113,202,0.3),0_4px_18px_0_rgba(59,113,202,0.2)] focus:outline-none focus:ring-0 active:bg-primary active:shadow-[0_8px_9px_-4px_rgba(59,113,202,0.3),0_4px_18px_0_rgba(59,113,202,0.2)] dark:shadow-[0_4px_9px_-4px_rgba(59,113,202,0.5)] dark:hover:shadow-[0_8px_9px_-4px_rgba(59,113,202,0.2),0_4px_18px_0_rgba(59,113,202,0.1)] dark:focus:shadow-[0_8px_9px_-4px_rgba(59,113,202,0.2),0_4px_18px_0_rgba(59,113,202,0.1)] dark:active:shadow-[0_8px_9px_-4px_rgba(59,113,202,0.2),0_4px_18px_0_rgba(59,113,202,0.1)]">
                            <svg class="w-4 h-4 text-white mr-2" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" fill="currentColor" viewBox="0 0 24 24">
                                <path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 12h14m-7 7V5"/>
                            </svg>
                            Create a User
                        </button>

                        <button
                            type="button"
                            id="bulk-delete-button"
                            data-te-ripple-init
                            data-te-ripple-color="light"
                            disabled
                            class="flex items-center rounded bg-red-600 px-4 py-2 text-xs font-medium uppercase leading-normal text-white shadow-[0_4px_9px_-4px_#3b71ca] transition duration-150 ease-in-out hover:bg-red-600 hover:shadow-[0_8px_9px_-4px_rgba(59,113,202,0.3),0_4px_18px_0_rgba(59,113,202,0.2)] focus:bg-red-600 focus:shadow-[0_8px_9px_-4px_rgba(59,113,202,0.3),0_4px_18px_0_rgba(59,113,202,0.2)] focus:outline-none focus:ring-0 active:bg-red-700 active:shadow-[0_8px_9px_-4px_rgba(59,113,202,0.3),0_4px_18px_0_rgba(59,113,202,0.2)] dark:shadow-[0_4px_9px_-4px_rgba(59,113,202,0.5)] dark:hover:shadow-[0_8px_9px_-4px_rgba(59,113,202,0.2),0_4px_18px_0_rgba(59,113,202,0.1)] dark:focus:shadow-[0_8px_9px_-4px_rgba(59,113,202,0.2),0_4px_18px_0_rgba(59,113,202,0.1)] dark:active:shadow-[0_8px_9px_-4px_rgba(59,113,202,0.2),0_4px_18px_0_rgba(59,113,202,0.1)]">
                            <svg class="w-4 h-4 text-white mr-2" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" fill="currentColor" viewBox="0 0 24 24">
                                <path fill-rule="evenodd" d="M8.6 2.6A2 2 0 0 1 10 2h4a2 2 0 0 1 2 2v2h3a1 1 0 1 1 0 2v12a2 2 0 0 1-2 2H7a2 2 0 0 1-2-2V8a1 1 0 0 1 0-2h3V4c0-.5.2-1 .6-1.4ZM10 6h4V4h-4v2Zm1 4a1 1 0 1 0-2 0v8a1 1 0 1 0 2 0v-8Zm4 0a1 1 0 1 0-2 0v8a1 1 0 1 0 2 0v-8Z" clip-rule="evenodd"/>
                            </svg>
                            Delete Selected Rows
                        </button>
                    </div>
                </div>

                <div id="datatable" class="relative"></div>
            </div>
        </div>
    </div>
</div>

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
        }
    </style>
@endpush

@push('scripts')
    @vite(['resources/js/list-users.js'])
@endpush
