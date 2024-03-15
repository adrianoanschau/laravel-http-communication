@props([
    'id',
    'title',
    'show' => false,
    'method' => 'POST',
    'action',
    'async',
    'reload'
])

<!-- Modal -->
<div
  data-te-modal-init
  class="fixed left-0 top-0 z-[1055] hidden h-full w-full overflow-y-auto overflow-x-hidden outline-none"
  id="{{$id}}"
  tabindex="-1"
  aria-labelledby="{{$id.'Label'}}"
  aria-hidden="true"
  style="z-index: 1055">
  <div
    data-te-modal-dialog-ref
    class="pointer-events-none relative w-auto translate-y-[-50px] opacity-0 transition-all duration-300 ease-in-out min-[576px]:mx-auto min-[576px]:mt-7 min-[576px]:max-w-[500px]"
    style="max-width: 500px; margin: 50px auto;">
    <div class="min-[576px]:shadow-[0_0.5rem_1rem_rgba(#000, 0.15)] pointer-events-auto relative flex w-full flex-col rounded-md border-none bg-white bg-clip-padding text-current shadow-lg outline-none dark:bg-neutral-600">
      <div class="flex flex-shrink-0 items-center justify-between rounded-t-md border-b-2 border-neutral-100 border-opacity-100 p-4 dark:border-opacity-50">
        <!--Modal title-->
        @if(isset($title))
            <h5 id="{{$id.'Label'}}" class="text-xl font-medium leading-normal text-neutral-800 dark:text-neutral-200">
            {{$title}}
            </h5>
        @endif

        <!--Close button-->
        <button
          type="button"
          class="box-content rounded-none border-none hover:no-underline hover:opacity-75 focus:opacity-100 focus:shadow-none focus:outline-none"
          data-te-modal-dismiss
          aria-label="Close">
          <svg
            xmlns="http://www.w3.org/2000/svg"
            fill="none"
            viewBox="0 0 24 24"
            stroke-width="1.5"
            stroke="currentColor"
            class="h-6 w-6">
            <path
              stroke-linecap="round"
              stroke-linejoin="round"
              d="M6 18L18 6M6 6l12 12" />
          </svg>
        </button>
      </div>

      <!--Modal body-->
      <div class="relative flex-auto p-4" data-te-modal-body-ref>
        <form id="{{$id}}Form"
            method="{{$method}}"
            action="{{$action}}"
            novalidate
            @isset($async)
            async
            @endisset
            @isset($reload)
            reload="{{$reload}}"
            @endisset
            >
            {{ $slot }}

            <!--Modal footer-->
            <div
              class="flex flex-shrink-0 flex-wrap items-center justify-end rounded-b-md border-t-2 border-neutral-100 border-opacity-100 pt-4 dark:border-opacity-50">
              <x-secondary-button class="mr-2 dismiss-modal" data-te-modal-dismiss>
                Close
              </x-secondary-button>

              <x-primary-button>
                Save
              </x-primary-button>
            </div>
        </form>
      </div>
    </div>
  </div>
</div>
