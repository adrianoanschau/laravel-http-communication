<div class="flex flex-col">
    <div class="bulk-actions flex justify-end mb-2 h-10">
        <div class="flex">
            {{ $actions }}
        </div>
    </div>

    <div class="overflow-x-auto sm:-mx-6 lg:-mx-8">
        <div class="inline-block min-w-full py-2 sm:px-6 lg:px-8">
            <div class="overflow-hidden">
                {{ $content }}
            </div>
        </div>
    </div>
</div>
