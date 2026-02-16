<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Control Panel') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900">
                    @can('admin')
                        @foreach ($users as $user)
                        <ol>
                        <li><img src="{{ asset(Storage::url($user->profile_photo)) }}" alt="Foto de {{ $user->name }}" style="width: 100px; border-radius: 50%;">{{ __("- Nombre: ".$user->name ." | - Username: ". $user->username ." | - Email: ". $user->email ." | - Rol: ". $user->role) }}</li>
                        </ol>
                        @endforeach
                    @endcan
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
