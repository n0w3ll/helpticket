<x-app-layout>
    <div class="max-w-2xl mx-auto p-4 sm:p-6 lg:p-8">
    <div class="flex flex-col justify-center items-center pt-6 sm:pt-0 bg-gray-100 dark:bg-gray-900">
            <h1 class="dark:text-white text-lg font-bold">Update Support Ticket</h1>
            <div class="w-full sm:max-w-xl mt-6 px-6 py-4 bg-white dark:bg-gray-800 shadow-md overflow-hidden sm:rounded-lg">
                <form method="POST" action="{{ route('ticket.update', $ticket->id) }}" enctype="multipart/form-data">
                    @csrf
                    @method('patch')

                    <div class="mt-4">
                        <x-input-label for="title" :value="__('Title')" />
                        <x-text-input id="title" class="block mt-1 w-full" type="text" name="title" value="{{ $ticket->title }}" autofocus />
                        <x-input-error :messages="$errors->get('title')" class="mt-2" />
                    </div>

                    <div class="mt-4">
                        <x-input-label for="description" :value="__('Description')" />
                        <x-textarea id="description" name="description" placeholder="Add description" value="{{ $ticket->description }}"/>
                        <x-input-error :messages="$errors->get('description')" class="mt-2" />
                    </div>

                    @if($ticket->attachment)
                        <a href="{{ '/storage/'. $ticket->attachment }}" target="_blank">
                            <div style="padding-top: 0.2em; padding-bottom: 0.2rem" class="mt-3 flex items-center  mx-auto space-x-2 text-sm px-2 bg-gray-200 text-gray-800 rounded-full w-1/3 border-red-300">
                                <div style="width: 0.4rem; height: 0.4rem" class="bg-green-500 rounded-full"></div>
                                <div class="px-3">View Attachment</div>
                            </div>
                        </a>
                    @endif

                    <div class="mt-4">
                        <x-input-label for="attachment" :value="__('Attachment (if any)')" />
                        <x-file-input name="attachment" id="attachment"/>
                        <x-input-error :messages="$errors->get('attachment')" class="mt-2" />
                    </div>

                    <div class="flex items-center justify-end mt-4">
                        <x-primary-button class="ml-3">
                            Update
                        </x-primary-button>
                    </div>

                </form>
            </div>
        </div>
    </div>
</x-app-layout>