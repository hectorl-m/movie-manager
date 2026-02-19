<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Catálogo de Películas') }}
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
                    <div class="border rounded-lg overflow-hidden shadow hover:shadow-lg transition">
                        @if($movie->poster_path)
                            <img src="{{ $movie->poster_path }}" alt="{{ $movie->title }}" class="w-full h-80 object-cover">
                        @else
                            <div class="w-full h-80 bg-gray-200 flex items-center justify-center text-gray-400">Sin póster</div>
                        @endif
                        
                        <div class="p-4 flex-grow flex flex-col justify-between">
                            <div>
                                <h3 class="font-bold text-lg leading-tight mb-1 truncate" title="{{ $movie->title }}">
                                    {{ $movie->title }}
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
                                                {{ $status === 'pending' ? '★ Pendiente' : 'Pendiente' }}
                                            </button>
                                        </form>

                                        <form action="{{ route('movies.list.toggle', $movie) }}" method="POST" class="flex-1">
                                            @csrf
                                            <input type="hidden" name="status" value="watched">
                                            <button type="submit" class="w-full text-xs font-semibold py-1.5 rounded transition {{ $status === 'watched' ? 'bg-green-600 text-white' : 'bg-green-100 text-green-800 hover:bg-green-200' }}">
                                                {{ $status === 'watched' ? '✓ Vista' : 'Vista' }}
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

            <div class="mt-8">
                {{ $movies->links() }} 
            </div>

        </div>
    </div>
</x-app-layout>