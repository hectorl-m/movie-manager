<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Movie Catalog') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            
            <div class="bg-white p-6 rounded-lg shadow-sm mb-6">
                <form action="{{ route('movies.index') }}" method="GET" class="flex flex-wrap gap-4 items-end">
                    
                    <div class="flex-grow min-w-[200px]">
                        <label class="block text-sm font-medium text-gray-700">Search by title</label>
                        <input type="text" name="search" value="{{ request('search') }}" 
                               class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500" 
                               placeholder="Ej: Hector saca un 10 la pelicula...">
                    </div>
                    
                    <div>
                        <label class="block text-sm font-medium text-gray-700">Genere</label>
                        <select name="genre" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500">
                            <option value="">Todos</option>
                            @foreach($genres as $g)
                                <option value="{{ $g->id }}" {{ request('genre') == $g->id ? 'selected' : '' }}>
                                    {{ $g->name }}
                                </option>
                            @endforeach
                        </select>
                    </div>

                    <div>
                        <label class="block text-sm font-medium text-gray-700">Year</label>
                        <input type="number" name="year" value="{{ request('year') }}" 
                               class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500" 
                               placeholder="Ej: 2023" min="1900" max="2100">
                    </div>

                    <div>
                        <label class="block text-sm font-medium text-gray-700">Order By</label>
                        <select name="sort" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500">
                            <option value="created_at" {{ request('sort') == 'created_at' ? 'selected' : '' }}>Last added</option>
                            <option value="title" {{ request('sort') == 'title' ? 'selected' : '' }}>Title</option>
                            <option value="year" {{ request('sort') == 'year' ? 'selected' : '' }}>Year</option>
                        </select>
                    </div>

                    <div class="flex items-center space-x-2">
                        <button type="submit" class="bg-indigo-600 text-white px-4 py-2 rounded-md hover:bg-indigo-700 transition">
                            Filter
                        </button>
                        <a href="{{ route('movies.index') }}" class="text-sm text-gray-600 hover:text-gray-900 underline">
                            Clear
                        </a>
                    </div>
                </form>
            </div>

            <div class="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-4 gap-6">
                @forelse($movies as $movie)
                    <div class="relative border rounded-lg overflow-hidden shadow hover:shadow-lg transition">
                        
                        @can('admin')
                        <form action="{{ route('movies.destroy', $movie) }}" method="POST" class="absolute top-2 right-2 z-10" onsubmit="return confirm('¿Estás seguro de que quieres borrar esta película de toda la base de datos?');">
                            @csrf
                            @method('DELETE')
                            <button type="submit" class="bg-red-600 text-white w-8 h-8 rounded-full flex items-center justify-center hover:bg-red-700 shadow-md transition" title="Eliminar película">
                                <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16" />
                                </svg>
                            </button>
                        </form>
                        @endcan

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
                                    {{ \Carbon\Carbon::parse($movie->release_date)->year ?? 'Sin fecha' }}
                                </p>

                                <div class="mt-4 pt-4 border-t border-gray-100 flex items-center justify-between">
                                    @php
                                        $userPivot = $movie->users->first();
                                        $status = $userPivot ? $userPivot->pivot->status : null;
                                    @endphp

                                    <div class="flex space-x-2 w-full">
                                        <form action="{{ route('movies.list.toggle', $movie) }}" method="POST" class="flex-1">
                                            @csrf
                                            <input type="hidden" name="status" value="pending">
                                            <button type="submit" class="w-full text-xs font-semibold py-1.5 rounded transition {{ $status === 'pending' ? 'bg-yellow-500 text-white' : 'bg-yellow-100 text-yellow-800 hover:bg-yellow-200' }}">
                                                {{ $status === 'pending' ? '★ Pending' : 'Pending' }}
                                            </button>
                                        </form>

                                        <form action="{{ route('movies.list.toggle', $movie) }}" method="POST" class="flex-1">
                                            @csrf
                                            <input type="hidden" name="status" value="watched">
                                            <button type="submit" class="w-full text-xs font-semibold py-1.5 rounded transition {{ $status === 'watched' ? 'bg-green-600 text-white' : 'bg-green-100 text-green-800 hover:bg-green-200' }}">
                                                {{ $status === 'watched' ? '✓ Watched' : 'Watched' }}
                                            </button>
                                        </form>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                @empty
                    <div class="col-span-full bg-white p-8 rounded-lg text-center text-gray-500">
                        <p class="text-lg">No se han encontrado películas con esos filtros.</p>
                        <p class="text-sm mt-2">Limpia la búsqueda o importa nuevas películas desde TMDB.</p>
                    </div>
                @endforelse
            </div>

            {{-- para que laravel genere las paginas de las peliculas --}}
            <div class="mt-8">
                {{ $movies->links() }} 
            </div>

        </div>
    </div>
</x-app-layout>