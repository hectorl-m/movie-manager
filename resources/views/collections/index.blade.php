<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('My Collections') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            
            @if(session('success'))
                <div class="mb-6 bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded relative">
                    {{ session('success') }}
                </div>
            @endif

            <div class="flex flex-col md:flex-row gap-6">
                
                <div class="w-full md:w-1/3">
                    <div class="bg-white p-6 rounded-lg shadow-sm border border-gray-100">
                        <h3 class="text-lg font-bold mb-4 text-gray-800">Create New Collection</h3>
                        
                        <form action="{{ route('collections.store') }}" method="POST">
                            @csrf
                            
                            <div class="mb-4">
                                <label for="name" class="block text-sm font-medium text-gray-700">Name *</label>
                                <input type="text" name="name" id="name" required class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500" placeholder="e.g. Favorite Scary Movies">
                                @error('name') <span class="text-red-500 text-xs">{{ $message }}</span> @enderror
                            </div>

                            <div class="mb-4">
                                <label for="description" class="block text-sm font-medium text-gray-700">Description (Optional)</label>
                                <textarea name="description" id="description" rows="3" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500" placeholder="What is this collection about?"></textarea>
                                @error('description') <span class="text-red-500 text-xs">{{ $message }}</span> @enderror
                            </div>

                            <div class="mb-6 flex items-center">
                                <input type="checkbox" name="is_public" id="is_public" class="rounded border-gray-300 text-indigo-600 shadow-sm focus:ring-indigo-500">
                                <label for="is_public" class="ml-2 block text-sm text-gray-700">
                                    Make it public
                                </label>
                            </div>

                            <button type="submit" class="w-full bg-indigo-600 text-white px-4 py-2 rounded-md hover:bg-indigo-700 transition font-semibold">
                                Create Collection
                            </button>
                        </form>
                    </div>
                </div>

                <div class="w-full md:w-2/3">
                    <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                        
                        @forelse($myCollections as $collection)
                            <div class="bg-white p-5 rounded-lg shadow-sm border border-gray-100 flex flex-col relative group hover:shadow-md transition">
                                
                                <form action="{{ route('collections.destroy', $collection) }}" method="POST" class="absolute top-4 right-4" onsubmit="return confirm('Are you sure you want to delete this collection?');">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="text-red-400 hover:text-red-600 transition" title="Delete Collection">
                                        <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16" />
                                        </svg>
                                    </button>
                                </form>

                                <div class="pr-8 mb-2">
                                    <h4 class="text-xl font-bold text-gray-800 truncate" title="{{ $collection->name }}">
                                        {{ $collection->name }}
                                    </h4>
                                    <div class="flex items-center gap-2 mt-1">
                                        @if($collection->is_public)
                                            <span class="text-xs bg-green-100 text-green-800 px-2 py-0.5 rounded-full font-semibold">🌍 Public</span>
                                        @else
                                            <span class="text-xs bg-gray-100 text-gray-600 px-2 py-0.5 rounded-full font-semibold">🔒 Private</span>
                                        @endif
                                        
                                        <span class="text-xs text-gray-500">{{ $collection->movies()->count() }} movies</span>
                                    </div>
                                </div>

                                <p class="text-sm text-gray-600 line-clamp-2 mb-4 flex-grow">
                                    {{ $collection->description ?: 'No description.' }}
                                </p>

                                <div class="mt-auto pt-4 border-t border-gray-50">
                                    <a href="{{ route('collections.show', $collection) }}" class="text-indigo-600 hover:text-indigo-800 text-sm font-semibold flex items-center">
                                        View Movies <span class="ml-1">&rarr;</span>
                                    </a>
                                </div>
                            </div>
                        @empty
                            <div class="col-span-full bg-white p-8 rounded-lg border border-dashed text-center text-gray-500">
                                <p class="text-lg">You don't have any collections yet.</p>
                                <p class="text-sm mt-1">Create your first one using the form on the left!</p>
                            </div>
                        @endforelse
                        
                    </div>
                </div>

            </div>
            <div class="mt-12 border-t pt-8">
                <h3 class="text-2xl font-bold mb-6 text-gray-800">Community Public Collections</h3>
                
                <div class="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-3 gap-6">
                    @forelse($publicCollections as $publicCollection)
                        <div class="bg-white p-5 rounded-lg shadow-sm border border-gray-100 flex flex-col hover:shadow-md transition">
                            
                            <div class="mb-2">
                                <h4 class="text-xl font-bold text-gray-800 truncate" title="{{ $publicCollection->name }}">
                                    {{ $publicCollection->name }}
                                </h4>
                                <div class="flex items-center gap-2 mt-2">
                                    <span class="text-xs bg-indigo-100 text-indigo-800 px-2 py-0.5 rounded-full font-semibold">
                                        👤 By {{ $publicCollection->user->name }}
                                    </span>
                                    <span class="text-xs text-gray-500">{{ $publicCollection->movies()->count() }} movies</span>
                                </div>
                            </div>

                            <p class="text-sm text-gray-600 line-clamp-2 mb-4 flex-grow">
                                {{ $publicCollection->description ?: 'No description.' }}
                            </p>

                            <div class="mt-auto pt-4 border-t border-gray-50">
                                <a href="{{ route('collections.show', $publicCollection) }}" class="text-indigo-600 hover:text-indigo-800 text-sm font-semibold flex items-center">
                                    View Collection <span class="ml-1">&rarr;</span>
                                </a>
                            </div>
                        </div>
                    @empty
                        <div class="col-span-full bg-gray-50 p-8 rounded-lg text-center text-gray-500 border border-dashed">
                            <p>No public collections from other users yet.</p>
                        </div>
                    @endforelse
                </div>
            </div>
        </div>
    </div>
</x-app-layout>