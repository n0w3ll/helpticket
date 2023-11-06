<x-app-layout>
    <div class="max-w-2xl mx-auto p-4 sm:p-6 lg:p-8">
        <div class="flex justify-between items-center">
            <h1 class="dark:text-white text-lg font-bold uppercase">Support Tickets</h1>
            
            <div class="flex flex-col justify-center mb-4">
                <a href="{{ route('ticket.create') }}" class="bg-gray-800 px-4 py-2 dark:bg-gray-200 rounded-md font-semibold text-xs text-white dark:text-gray-800 uppercase">
                    + New Ticket
                </a>
            </div>
        </div>
        <form action="{{ route('ticket.index') }}" method="get">
            <div class="flex justify-between mt-4">
                <select name="search" id="search" class="w-4/5 border-gray-300 dark:border-gray-700 dark:bg-gray-900 dark:text-gray-300 focus:border-indigo-500 dark:focus:border-indigo-600 focus:ring-indigo-500 dark:focus:ring-indigo-600 rounded-md shadow-sm">
                    <option value="open" {{ $searched === 'open' ? 'selected' : '' }}>Open</option>
                    <option value="resolved" {{ $searched === 'resolved' ? 'selected' : '' }}>Resolved</option>
                    <option value="rejected" {{ $searched === 'rejected' ? 'selected' : '' }}>Rejected</option>
                </select>
                <x-primary-button class="w-1/5 justify-center ml-2">Filter</x-primary-button>
            </div>
        </form>
        
        <div class="flex flex-col justify-center items-center pt-6 sm:pt-0 bg-gray-100 dark:bg-gray-900">
            
            @forelse($tickets as $ticket)
            <a href="{{ route('ticket.show', $ticket->id) }}" class="w-full">
                <div class="w-full mt-6 px-6 py-4 bg-white dark:bg-gray-800 shadow-md overflow-hidden sm:rounded-lg @if($ticket->status == 'rejected') border-l-4 border-red-500 dark:border-red-500
                                @elseif($ticket->status == 'resolved') border-l-4 border-green-500 dark:border-green-300
                                @else border-l-4 border-blue-500 dark:border-blue-300 @endif">
                    <div class="only:border-transparent last:border-transparent border-b-2 border-gray-200 dark:border-gray-600">
                        
                        <div class="flex justify-between pb-4">
                            @if(auth()->user()->isAdmin)
                            <div class="flex space-x-2 text-sm text-gray-500">
                                <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="w-5 h-5">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M15.75 6a3.75 3.75 0 11-7.5 0 3.75 3.75 0 017.5 0zM4.501 20.118a7.5 7.5 0 0114.998 0A17.933 17.933 0 0112 21.75c-2.676 0-5.216-.584-7.499-1.632z" />
                                </svg>
                                <span>{{ $ticket->user->name }}</span>
                            </div>
                            @endif
                            <p class="text-gray-700 dark:text-gray-500 text-sm ml-auto">
                            @if ($ticket->created_at->eq($ticket->updated_at))
                                {{ $ticket->created_at->diffForHumans() }}
                            @else
                                @if ($ticket->user_id == $ticket->status_changed_by_id)
                                    <small class="text-sm text-gray-500 ml-3"> &middot; {{ __('edited') }} </small>
                                @endif
                                {{ $ticket->updated_at->diffForHumans() }}
                            @endif
                            </p>
                        </div>
                        <p class="font-bold text-gray-700 dark:text-gray-400 text-lg pb-2">{{ $ticket->title }}</p>
                        <p class="text-xs dark:text-gray-400 mb-4">Status : 
                            <span class="uppercase text-bold 
                                @if($ticket->status == 'rejected') text-red-500 dark:text-red-300
                                @elseif($ticket->status == 'resolved') text-green-500 dark:text-green-300
                                @else text-blue-500 dark:text-blue-300 @endif
                                ">{{ $ticket->status }}</span>
                        </p>
                    </div>
                </div>
            </a>
            @empty
                <div class="w-full mt-6 px-6 py-4 bg-white dark:bg-gray-800 shadow-md overflow-hidden sm:rounded-lg">
                    <div>
                        <p class="text-gray-500 dark:text-gray-400 text-sm">No tickets available</p>
                    </div>
                </div>
            @endforelse
            <div class="mt-3 w-full">
            {!! $tickets->appends(Illuminate\Support\Facades\Request::except('page'))->links() !!}
            </div>
        </div>
    </div>
</x-app-layout>