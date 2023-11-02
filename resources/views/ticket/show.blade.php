<x-app-layout>
    <div class="max-w-2xl mx-auto p-4 sm:p-6 lg:p-8">
        <div class="flex flex-col justify-center items-center pt-6 sm:pt-0 bg-gray-100 dark:bg-gray-900">
            <h1 class="dark:text-white text-lg font-bold">{{ $ticket->title }}</h1>
            <div class="w-full sm:max-w-xl mt-6 px-6 py-4 bg-white dark:bg-gray-800 shadow-md overflow-hidden sm:rounded-lg">
                <div class="flex justify-between">
                    <div class="py-2 flex">
                        <p class="text-gray-700 dark:text-gray-400  text-sm">{{ $ticket->created_at->diffForHumans() }}</p>
                        @unless ($ticket->created_at->eq($ticket->updated_at))
                        @if ($ticket->user_id == $ticket->status_changed_by_id)
                        <small class="text-sm text-gray-500 ml-3"> &middot; {{ __('edited') }}</small>
                        @else
                        <small class="text-sm text-gray-500 ml-3"> &middot; {{ __('status updated') }}</small>
                        @endif
                        @endunless
                    </div>
                    <x-dropdown>
                        <x-slot name="trigger">
                            <button>
                                <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 text-gray-400" viewBox="0 0 20 20" fill="currentColor">
                                    <path d="M6 10a2 2 0 11-4 0 2 2 0 014 0zM12 10a2 2 0 11-4 0 2 2 0 014 0zM16 12a2 2 0 100-4 2 2 0 000 4z" />
                                </svg>
                            </button>
                        </x-slot>
                        <x-slot name="content">
                            <x-dropdown-link :href="route('ticket.edit', $ticket)">
                                {{ __('Edit') }}
                            </x-dropdown-link>

                            <form action="{{ route('ticket.destroy', $ticket) }}" method="POST">
                                @csrf
                                @method('delete')

                                <x-dropdown-link :href="route('ticket.destroy', $ticket)" onclick="event.preventDefault();this.closest('form').submit();">
                                    {{ __('Delete') }}
                                </x-dropdown-link>
                            </form>
                        </x-slot>
                    </x-dropdown>
                </div>

                <p class="text-gray-700 dark:text-gray-300 py-4">{{ $ticket->description }}</p>
                @if($ticket->attachment)
                    <div style="padding-top: 0.2em; padding-bottom: 0.2rem" class="mt-3 flex items-center  space-x-2 text-sm px-2 bg-gray-200 text-gray-800 rounded-full w-1/4">

                        <div style="width: 0.4rem; height: 0.4rem" class="bg-green-500 rounded-full"></div>
                        <a href="{{ '/storage/'. $ticket->attachment }}" target="_blank" class="px-2">Attachment</a>
                    </div>
                @endif
                    <div class="flex mt-3 space-x-2 float-right">
                        @if(auth()->user()->isAdmin && $ticket->status == 'open')
                        <form action="{{ route('ticket.update', $ticket )}}" method="post">
                            @csrf
                            @method('patch')
                            <input type="hidden" name="status" value="resolved">
                            <x-primary-button>Resolve</x-primary-button>
                        </form>
                        <form action="{{ route('ticket.update', $ticket )}}" method="post">
                            @csrf
                            @method('patch')
                            <input type="hidden" name="status" value="rejected">
                            <x-primary-button class="bg-red-400 hover:bg-red-500 text-gray-800">Reject</x-primary-button>
                        </form>
                        @else
                        <p class="text-sm text-gray-500">Status :
                            <span class="font-bold uppercase">{{ $ticket->status }}</span>
                        </p>
                        @endif
                    </div>
            </div>
            <!-- Comment -->
            <div class="w-full sm:max-w-xl mt-6 px-6 py-4 bg-white dark:bg-gray-800 shadow-md overflow-hidden sm:rounded-lg">
                <form method="post" action="{{ route('comment.store') }}">
                    @csrf

                    <input type="hidden" name="ticket_id" value="{{ $ticket->id }}">
                    <div class="mt-4">
                        <x-input-label for="description" :value="__('Comment')" />
                        <x-textarea id="comment" name="comment" placeholder="Post a comment" value="" />
                        <x-input-error :messages="$errors->get('comment')" class="mt-2" />
                    </div>

                    <div class="flex items-center justify-end mt-4">
                        <x-primary-button class="ml-3">
                            Comment
                        </x-primary-button>
                    </div>

                </form>
            </div>

            <div class="w-full sm:max-w-xl mt-6 px-6 py-4 bg-white dark:bg-gray-800 shadow-md overflow-hidden sm:rounded-lg">
                <div class="flex space-x-2">
                    <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="w-6 h-6 text-gray-500">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M7.5 8.25h9m-9 3H12m-9.75 1.51c0 1.6 1.123 2.994 2.707 3.227 1.129.166 2.27.293 3.423.379.35.026.67.21.865.501L12 21l2.755-4.133a1.14 1.14 0 01.865-.501 48.172 48.172 0 003.423-.379c1.584-.233 2.707-1.626 2.707-3.228V6.741c0-1.602-1.123-2.995-2.707-3.228A48.394 48.394 0 0012 3c-2.392 0-4.744.175-7.043.513C3.373 3.746 2.25 5.14 2.25 6.741v6.018z" />
                    </svg>

                    <h1 class="text-gray-500 dark:text-white font-bold mb-3">Comments :</h1>
                </div>
                @forelse($ticket->comments as $comment)
                    <div class="mt-5 only:border-transparent last:border-transparent border-b-2 border-gray-200 dark:border-gray-700">
                        <p class="text-gray-900 dark:text-gray-300 font-bold">{{ $comment->comment }}</p>
                        <div class="flex justify-between text-sm mt-2 mb-3 ">
                            <p class="text-bold text-gray-700 dark:text-gray-400">&middot; {{ $comment->user->name }}</p>
                            <p class="text-gray-500 dark:text-gray-400 text-xs">{{ $comment->created_at->diffForHumans() }}</p>
                        </div>
                    </div>
                @empty
                    <p class="text-gray-900 dark:text-gray-400 text-sm mt-5">{{ __('No comments available') }}</p>
                @endforelse
            </div>

        </div>
    </div>
</x-app-layout>