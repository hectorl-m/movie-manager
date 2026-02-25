<x-app-layout>
    <x-slot name="header">
        <div class="flex items-center justify-between">
            <h2 class="font-semibold text-xl text-gray-800 leading-tight flex items-center gap-2">
                {{ $collection->name }}
                @if($collection->is_public)
                    <span class="text-xs bg-green-100 text-green-800 px-2 py-0.5 rounded-full font-semibold">🌍 Public</span>
                @else
                    <span class="text-xs bg-gray-100 text-gray-600 px-2 py-0.5 rounded-full font-semibold">🔒 Private</span>
                @endif
            </h2>
            <a href="{{ route('collections.index') }}" class="text-sm text-indigo-600 hover:text-indigo-800">&larr; Back to Collections</a>
        </div>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            
            @if(session('success'))
                <div class="mb-6 bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded relative">
                    {{ session('success') }}
                </div>
            @endif

            @if($collection->description)
                <div class="bg-white p-6 rounded-lg shadow-sm mb-8 text-gray-700">
                    {{ $collection->description }}
                </div>
            @endif

            <div class="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-4 gap-6">
                @forelse($collection->movies as $movie)
                    <div class="relative bg-white border rounded-lg overflow-hidden shadow hover:shadow-lg transition flex flex-col">
                        
                        @if($collection->user_id === auth()->id())
                            <form action="{{ route('collections.removeMovie', [$collection, $movie]) }}" method="POST" class="absolute top-2 right-2 z-10" onsubmit="return confirm('Remove this movie from the collection?');">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="bg-red-600 text-white w-8 h-8 rounded-full flex items-center justify-center hover:bg-red-700 shadow-md transition" title="Remove from collection">
                                    <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                                    </svg>
                                </button>
                            </form>
                        @endif

                        @if($movie->poster_path)
                            <img src="{{ $movie->poster_path }}" alt="{{ $movie->title }}" class="w-full h-80 object-cover">
                        @else
                            <div class="w-full h-80 bg-gray-200 flex items-center justify-center text-gray-400">Sin póster</div>
                        @endif
                        
                        <div class="p-4 flex-grow flex flex-col justify-between">
                            <div>
                                <h3 class="font-bold text-lg leading-tight mb-1 truncate" title="{{ $movie->title }}">
                                    <a href="{{ route('movies.show', $movie) }}" class="hover:text-indigo-600 hover:underline">
                                        {{ $movie->title }}
                                    </a>
                                </h3>
                                <p class="text-sm text-gray-500">
                                    {{ \Carbon\Carbon::parse($movie->release_date)->year ?? 'N/A' }}
                                </p>
                            </div>
                        </div>
                    </div>
                @empty
                    <div class="col-span-full bg-white p-8 rounded-lg text-center text-gray-500">
                        <p class="text-lg">This collection is empty.</p>
                        <p class="text-sm mt-2">Go to the catalog and add some movies!</p>
                    </div>
                @endforelse
            </div>

        </div>
    </div>
</x-app-layout>