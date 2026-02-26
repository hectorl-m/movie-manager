<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Control Panel') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            @can('admin')
                @foreach ($users as $user)
                    <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg" style="margin:10px 0px;">
                        <div class="p-6 text-gray-900">
                            <ul class="flex items-center justify-between">
                                <li><img src="{{ asset(Storage::url($user->profile_photo)) }}" alt="Foto de {{ $user->name }}" class="w-20 h-20 rounded-full object-cover shadow-sm"></li>
                                <li>{{ "- Nombre: ".$user->name }}</li>
                                <li>{{ "- Username: ". $user->username }}</li>
                                <li>{{ "- Email: ". $user->email }}</li>
                                <li>{{ "- Rol: ". $user->role }}</li>
                            </ul>
                        </div>
                    </div>
                @endforeach
            @endcan
        </div>
    </div>
</x-app-layout>
