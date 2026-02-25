<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Welcome back, ') }} {{ Auth::user()->name }}! 🎬
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            
            <div class="grid grid-cols-1 md:grid-cols-3 gap-6 mb-8">
                <div class="bg-white overflow-hidden shadow-sm rounded-lg p-6 border-l-4 border-green-500 flex items-center justify-between">
                    <div>
                        <p class="text-sm font-medium text-gray-500 uppercase tracking-wide">Movies Watched</p>
                        <p class="text-3xl font-bold text-gray-900 mt-1">{{ $watchedCount }}</p>
                    </div>
                    <div class="text-green-500 text-4xl">✓</div>
                </div>

                <div class="bg-white overflow-hidden shadow-sm rounded-lg p-6 border-l-4 border-yellow-500 flex items-center justify-between">
                    <div>
                        <p class="text-sm font-medium text-gray-500 uppercase tracking-wide">Watchlist</p>
                        <p class="text-3xl font-bold text-gray-900 mt-1">{{ $pendingCount }}</p>
                    </div>
                    <div class="text-yellow-500 text-4xl">★</div>
                </div>

                <div class="bg-white overflow-hidden shadow-sm rounded-lg p-6 border-l-4 border-indigo-500 flex items-center justify-between">
                    <div>
                        <p class="text-sm font-medium text-gray-500 uppercase tracking-wide">Your Reviews</p>
                        <p class="text-3xl font-bold text-gray-900 mt-1">{{ $reviewsCount }}</p>
                    </div>
                    <div class="text-indigo-500 text-4xl">✍️</div>
                </div>
            </div>

            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                <div class="bg-white overflow-hidden shadow-sm rounded-lg p-6 border border-gray-100">
                    <h3 class="font-bold text-lg mb-4 text-gray-800">Quick Actions</h3>
                    <div class="space-y-3">
                        <a href="{{ route('tmdb.index') }}" class="block w-full text-center bg-indigo-50 text-indigo-700 hover:bg-indigo-100 font-semibold py-3 rounded-md transition">
                            🔍 Discover New Movies
                        </a>
                        <a href="{{ route('movies.index') }}" class="block w-full text-center bg-gray-50 text-gray-700 hover:bg-gray-100 font-semibold py-3 rounded-md transition border border-gray-200">
                            📚 Go to My Catalog
                        </a>
                        <a href="{{ route('collections.index') }}" class="block w-full text-center bg-gray-50 text-gray-700 hover:bg-gray-100 font-semibold py-3 rounded-md transition border border-gray-200">
                            📁 Manage My Collections
                        </a>
                    </div>
                </div>

                <div class="bg-white overflow-hidden shadow-sm rounded-lg p-6 border border-gray-100">
                    <h3 class="font-bold text-lg mb-4 text-gray-800">Your Recent Activity</h3>
                    
                    @if($recentReviews->count() > 0)
                        <div class="space-y-4">
                            @foreach($recentReviews as $review)
                                <div class="border-b border-gray-100 pb-3 last:border-0 last:pb-0">
                                    <p class="font-semibold text-gray-800">
                                        <a href="{{ route('movies.show', $review->movie) }}" class="hover:text-indigo-600">
                                            {{ $review->movie->title }}
                                        </a>
                                    </p>
                                    <div class="flex items-center justify-between mt-1">
                                        <span class="text-xs text-yellow-600 font-bold bg-yellow-50 px-2 py-0.5 rounded border border-yellow-200">
                                            {{ $review->rating }}/10 ⭐
                                        </span>
                                        <span class="text-xs text-gray-400">{{ $review->created_at->diffForHumans() }}</span>
                                    </div>
                                    @if($review->content)
                                        <p class="text-sm text-gray-600 mt-2 line-clamp-2 italic">"{{ $review->content }}"</p>
                                    @endif
                                </div>
                            @endforeach
                        </div>
                    @else
                        <div class="text-center py-6 text-gray-500">
                            <p>You haven't written any reviews yet.</p>
                        </div>
                    @endif
                </div>
            </div>

        </div>
    </div>
</x-app-layout>