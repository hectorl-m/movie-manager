<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Search Movies') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900">
                    {{-- 1. FORMULARIO DE BÚSQUEDA --}}
                    <form action="{{ route('tmdb.search') }}" method="get" class="flex gap-4 mb-8">
                        <div class="flex-grow">
                            <label for="search" class="sr-only">Buscar película</label>
                            <input type="text" name="search" id="search" placeholder="Ej: Hector saca un 10 la pelicula..." value="{{ request('search') }}" required class="w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500" />
                        </div>
                        <button type="submit" class="bg-indigo-600 text-white px-4 py-2 rounded-md hover:bg-indigo-700 transition">Search</button>
                    </form>

                    @if(isset($movies) && count($movies) > 0)
                        <h3 class="text-lg font-bold mb-4">Results found:</h3>
                        
                        {{-- Para cada pelicula encontrada: --}}
                        <div class="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-4 gap-6">
                            @foreach($movies as $movie)
                                <div class="border rounded-lg overflow-hidden shadow hover:shadow-lg transition">
                                    @if($movie['poster_path'])
                                        <img src="https://image.tmdb.org/t/p/w500{{ $movie['poster_path'] }}" alt="{{ $movie['title'] }}" class="w-full h-64 object-cover">
                                    @else
                                        <div class="w-full h-64 bg-gray-200 flex items-center justify-center text-gray-500">Sin imagen</div>
                                    @endif

                                    <div class="p-4">
                                        <h4 class="font-bold text-lg truncate" title="{{ $movie['title'] }}">{{ $movie['title'] }}</h4>
                                        <p class="text-gray-600 text-sm mb-4">{{ \Carbon\Carbon::parse($movie['release_date'] ?? '')->year ?? 'Sin fecha' }}</p>

                                        {{-- Enviamos el ID a tmdb.store para guardarla --}}
                                        <form action="{{ route('tmdb.store') }}" method="post">
                                            @csrf
                                            <input type="hidden" name="tmdb_id" value="{{ $movie['id'] }}">
                                            <button type="submit" class="w-full bg-green-600 text-white py-2 rounded hover:bg-green-700 transition text-sm">
                                                Import to catalog
                                            </button>
                                        </form>
                                    </div>
                                </div>
                            @endforeach
                        </div>
                    @elseif(isset($movies))
                        <p class="text-gray-500 text-center">No se encontraron películas con ese nombre.</p>
                    @endif
                </div>
            </div>
        </div>
    </div>
</x-app-layout>