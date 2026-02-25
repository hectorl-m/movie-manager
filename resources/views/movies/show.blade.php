<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ $movie->title }} ({{ \Carbon\Carbon::parse($movie->release_date)->year ?? 'N/A' }})
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            
            @if(session('success'))
                <div class="mb-4 bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded relative">
                    {{ session('success') }}
                </div>
            @endif

            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg flex flex-col md:flex-row">
                
                <div class="w-full md:w-1/3 p-6">
                    @if($movie->poster_path)
                        <img src="{{ $movie->poster_path }}" alt="{{ $movie->title }}" class="w-full rounded-lg shadow-md">
                    @else
                        <div class="w-full h-96 bg-gray-200 flex items-center justify-center text-gray-400 rounded-lg">Sin póster</div>
                    @endif
                </div>

                <div class="w-full md:w-2/3 p-6 flex flex-col justify-between">
                    <div>
                        <h1 class="text-3xl font-bold mb-2">{{ $movie->title }}</h1>
                        
                        <div class="flex flex-wrap items-center gap-4 text-sm text-gray-600 mb-6">
                            <span>📅 {{ \Carbon\Carbon::parse($movie->release_date)->format('d/m/Y') }}</span>
                            <span>⏱ {{ $movie->runtime ? $movie->runtime . ' min' : 'Unknown' }}</span>
                            <span class="font-bold text-yellow-600">
                                ⭐ {{ $averageRating ? number_format($averageRating, 1) . '/10' : 'No ratings' }}
                            </span>
                        </div>

                        <div class="mb-6 flex flex-wrap gap-2">
                            @foreach($movie->genres as $genre)
                                <span class="bg-indigo-100 text-indigo-800 text-xs font-semibold px-2.5 py-0.5 rounded">
                                    {{ $genre->name }}
                                </span>
                            @endforeach
                        </div>

                        <div class="mb-8">
                            <h3 class="text-lg font-bold mb-2">Synopsis</h3>
                            <p class="text-gray-700 leading-relaxed">
                                {{ $movie->overview ?: 'There is no synopsis available for this film.' }}
                            </p>
                        </div>
                    </div>

                    <div class="mt-auto border-t pt-6">
                        <h3 class="text-md font-bold mb-3">Your personal list</h3>
                        @php
                            // el usuario actual tiene esta película en su lista?
                            $userPivot = $movie->users()->where('user_id', auth()->id())->first();
                            $status = $userPivot ? $userPivot->pivot->status : null;
                        @endphp

                        <div class="flex space-x-4 max-w-sm">
                            <form action="{{ route('movies.list.toggle', $movie) }}" method="POST" class="flex-1">
                                @csrf
                                <input type="hidden" name="status" value="pending">
                                <button type="submit" class="w-full font-semibold py-2 rounded transition border {{ $status === 'pending' ? 'bg-yellow-500 text-white border-yellow-500' : 'bg-white text-yellow-600 border-yellow-500 hover:bg-yellow-50' }}">
                                    {{ $status === 'pending' ? '★ Pending' : 'Add to Pending' }}
                                </button>
                            </form>

                            <form action="{{ route('movies.list.toggle', $movie) }}" method="POST" class="flex-1">
                                @csrf
                                <input type="hidden" name="status" value="watched">
                                <button type="submit" class="w-full font-semibold py-2 rounded transition border {{ $status === 'watched' ? 'bg-green-600 text-white border-green-600' : 'bg-white text-green-600 border-green-600 hover:bg-green-50' }}">
                                    {{ $status === 'watched' ? '✓ Watched' : 'Mark as View' }}
                                </button>
                            </form>
                        </div>
                    </div>

                    @if($collections->count() > 0)
                        <div class="mt-6 border-t pt-6">
                            <h3 class="text-md font-bold mb-3">Add to Collection</h3>
                            <form action="{{ route('collections.addMovie', $movie) }}" method="POST" class="flex items-center gap-2 max-w-sm">
                                @csrf
                                <select name="collection_id" required class="block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500">
                                    <option value="">Select a collection...</option>
                                    @foreach($collections as $collection)
                                        @php
                                            $alreadyIn = $collection->movies->contains($movie->id);
                                        @endphp
                                        <option value="{{ $collection->id }}" {{ $alreadyIn ? 'disabled' : '' }}>
                                            {{ $collection->name }} {{ $alreadyIn ? '(Already added)' : '' }}
                                        </option>
                                    @endforeach
                                </select>
                                <button type="submit" class="bg-indigo-600 text-white px-4 py-2 rounded-md hover:bg-indigo-700 transition font-semibold whitespace-nowrap">
                                    Add
                                </button>
                            </form>
                        </div>
                    @else
                        <div class="mt-6 border-t pt-6">
                            <p class="text-sm text-gray-500">You don't have any collections yet. <a href="{{ route('collections.index') }}" class="text-indigo-600 hover:underline">Create one here</a>.</p>
                        </div>
                    @endif
                    
                </div>
            </div>

            {{-- reviews y tal --}}
            <div class="mt-8 bg-white overflow-hidden shadow-sm sm:rounded-lg p-6">
                <h3 class="text-2xl font-bold mb-6">Reviews and Ratings</h3>

                @php
                    // el usuario actual ya ha dejado una reseña.
                    $userReview = $movie->reviews->where('user_id', auth()->id())->first();
                @endphp

                @if(!$userReview)
                    <form action="{{ route('reviews.store', $movie) }}" method="POST" class="mb-10 bg-gray-50 p-6 rounded-lg border border-gray-200">
                        @csrf
                        <h4 class="font-bold text-lg mb-4 text-indigo-700">Leave your review</h4>
                        
                        <div class="flex flex-wrap gap-4 mb-4">
                            <div>
                                <label class="block text-sm font-medium text-gray-700">Rating *</label>
                                <select name="rating" required class="mt-1 block w-32 rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500">
                                    <option value="">Select...</option>
                                    @for($i = 10; $i >= 1; $i--)
                                        <option value="{{ $i }}">{{ $i }}/10</option>
                                    @endfor
                                </select>
                            </div>
                        </div>

                        <div class="mb-4">
                            <label class="block text-sm font-medium text-gray-700">Your comment (Optional)</label>
                            <textarea name="content" rows="3" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500" placeholder="What did you think about the movie?"></textarea>
                        </div>

                        <button type="submit" class="bg-indigo-600 text-white px-6 py-2 rounded-md hover:bg-indigo-700 transition font-semibold">
                            Submit Review
                        </button>
                    </form>
                @else
                    <div class="mb-10 bg-indigo-50 p-4 rounded-lg border border-indigo-100 flex items-center justify-between">
                        <p class="text-indigo-800">
                            You have already rated this movie with a <span class="font-bold">{{ $userReview->rating }}/10 ⭐</span>.
                        </p>
                    </div>
                @endif

                <div class="space-y-6">
                    <h4 class="font-bold text-gray-700 border-b pb-2">Community Reviews</h4>
                    
                    @forelse($movie->reviews as $review)
                        {{-- si auth()->user() devuelve un null ni hace el can() gracias a la '?' --}}
                        @if($review->is_visible || auth()->user()?->can('admin'))
                            
                            <div class="p-4 rounded-lg border shadow-sm flex flex-col gap-2 relative {{ $review->is_visible ? 'bg-white' : 'bg-gray-100 opacity-60' }}">
                                
                                @can('admin')
                                    <form action="{{ route('reviews.toggleVisibility', $review) }}" method="POST" class="absolute top-4 right-4">
                                        @csrf
                                        @method('PATCH')
                                        <button type="submit" class="text-xs font-bold px-2 py-1 rounded border {{ $review->is_visible ? 'text-orange-600 border-orange-200 bg-orange-50 hover:bg-orange-100' : 'text-green-600 border-green-200 bg-green-50 hover:bg-green-100' }}">
                                            {{ $review->is_visible ? 'Hide' : 'Show' }}
                                        </button>
                                    </form>
                                @endcan

                                <div class="flex items-center justify-between mb-2 pr-16">
                                    <div class="flex items-center gap-2">
                                        
                                        <div class="w-8 h-8 bg-indigo-100 text-indigo-700 rounded-full flex items-center justify-center font-bold overflow-hidden">
                                            <img src="{{ asset(Storage::url($review->user->profile_photo)) }}" style="width: 40px; border-radius: 50%; object-fit: cover;">
                                        </div>
                                        <span class="font-bold">{{ $review->user->name }}</span>

                                        @if(!$review->is_visible)
                                            <span class="ml-2 text-xs font-bold text-red-500 bg-red-100 px-2 py-0.5 rounded">HIDDEN</span>
                                        @endif

                                    </div>
                                    <span class="text-yellow-500 font-bold bg-yellow-50 px-2 py-1 rounded text-sm border border-yellow-200">
                                        {{ $review->rating }}/10 ⭐
                                    </span>
                                </div>
                                
                                @if($review->content)
                                    <p class="text-gray-700 mt-2">{{ $review->content }}</p>
                                @endif
                                
                                <p class="text-xs text-gray-400 mt-3 text-right">
                                    Posted on {{ $review->created_at->format('M d, Y') }}
                                </p>
                            </div>
                        @endif
                    @empty
                        <div class="text-center py-8 text-gray-500 bg-gray-50 rounded-lg border border-dashed">
                            <p>No reviews yet.</p>
                            <p class="text-sm mt-1">Be the first to share your opinion!</p>
                        </div>
                    @endforelse
                </div>
            </div>

        </div>
    </div>
</x-app-layout>