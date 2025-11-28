<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Dashboard') }}
        </h2>
    </x-slot>

    @section('content')
        <div class="bg-white p-7 max-w-6xl mx-auto px-6 md:px-12 xl:px-6 pt-32 min-h-screen">
            <h1 class=" text-4xl font-bold mb-8">
                Bonjour {{ auth()->user()->prenomutilisateur }} !
            </h1>
            <form method="POST" action="{{ route('logout') }}">
                @csrf
                <button type="submit" class="inline-flex items-center px-4 py-2 bg-red-5qqqqqq00 border border-transparent rounded-md font-semibold text-white hover:bg-red-700 focus:outline-none focus:ring-2 focus:ring-red-300">
                    Se déconnecter
                </button>
            </form>
        </div>
    @endsection

</x-app-layout>
