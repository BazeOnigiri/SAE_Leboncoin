<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">Mes favoris</h2>
    </x-slot>

    @section('content')
    <div class="bg-[#f8f9fb] min-h-screen py-8">
        <div class="max-w-5xl mx-auto px-4 sm:px-6 lg:px-8">
            <h1 class="text-3xl font-bold text-gray-900 mb-6">Mes favoris</h1>
            
            <div class="flex border-b border-gray-200 mb-6 text-sm font-medium" id="favoris-tabs">
                <button type="button" data-target="tab-annonces" class="tab-btn px-6 py-3 border-b-2 border-orange-600 text-orange-600 font-bold">Annonces sauvegardées (0)</button>
                <button type="button" data-target="tab-recherches" class="tab-btn px-6 py-3 text-gray-500 hover:text-gray-700">Recherches sauvegardées (0)</button>
            </div>

            <div id="tab-annonces">
                <div class="bg-white p-12 rounded-xl shadow-sm text-center border border-gray-200">
                    <div class="text-5xl mb-4">❤️</div>
                    <h3 class="text-xl font-bold text-gray-900">Vous n'avez aucune annonce favorite</h3>
                    <p class="text-gray-500 mt-2 mb-6">Explorez les annonces et cliquez sur le cœur pour les retrouver ici.</p>
                    <a href="{{ route('home') }}" class="bg-orange-600 text-white px-6 py-3 rounded-xl font-bold hover:bg-orange-700 transition">Explorer les annonces</a>
                </div>
            </div>

            <div id="tab-recherches" class="hidden">
                <div class="bg-white p-12 rounded-xl shadow-sm text-center border border-gray-200">
                    <div class="text-5xl mb-4">🔍</div>
                    <h3 class="text-xl font-bold text-gray-900">Vous n'avez aucune recherche sauvegardée</h3>
                    <p class="text-gray-500 mt-2 mb-6">Sauvegardez vos critères de recherche pour gagner du temps.</p>
                    <a href="{{ route('home') }}" class="bg-orange-600 text-white px-6 py-3 rounded-xl font-bold hover:bg-orange-700 transition">Faire une recherche</a>
                </div>
            </div>
        </div>
    </div>
    @endsection

    @push('scripts')
    <script>
        document.addEventListener('DOMContentLoaded', () => {
            const buttons = document.querySelectorAll('#favoris-tabs .tab-btn');
            const panels = {
                'tab-annonces': document.getElementById('tab-annonces'),
                'tab-recherches': document.getElementById('tab-recherches'),
            };

            const setActive = (target) => {
                buttons.forEach(btn => {
                    const isActive = btn.dataset.target === target;
                    btn.classList.toggle('border-b-2', isActive);
                    btn.classList.toggle('border-orange-600', isActive);
                    btn.classList.toggle('text-orange-600', isActive);
                    btn.classList.toggle('font-bold', isActive);
                    btn.classList.toggle('text-gray-500', !isActive);
                });
                Object.entries(panels).forEach(([key, panel]) => {
                    if (!panel) return;
                    panel.classList.toggle('hidden', key !== target);
                });
            };

            buttons.forEach(btn => {
                btn.addEventListener('click', () => setActive(btn.dataset.target));
            });
        });
    </script>
    @endpush
</x-app-layout>
