<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Gestion des Incidents') }}
        </h2>
    </x-slot>

    <div class="bg-[#f8f9fb] min-h-screen py-8" x-data="{ tab: 'en-cours' }">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            
            {{-- Système d'onglets --}}
            <div class="flex gap-4 mb-6 border-b border-gray-200">
                <button @click="tab = 'en-cours'" 
                    :class="tab === 'en-cours' ? 'border-blue-600 text-blue-600' : 'border-transparent text-gray-500 hover:text-gray-700'"
                    class="pb-4 px-2 font-bold text-sm border-b-2 transition-colors relative">
                    Incidents à traiter
                    <span class="ml-2 bg-blue-100 text-blue-700 px-2 py-0.5 rounded-full text-xs">
                        {{ $incidentsEnCours->count() }}
                    </span>
                </button>
                <button @click="tab = 'archives'" 
                    :class="tab === 'archives' ? 'border-gray-600 text-gray-900' : 'border-transparent text-gray-500 hover:text-gray-700'"
                    class="pb-4 px-2 font-bold text-sm border-b-2 transition-colors">
                    Archives (Classés / Remboursés)
                </button>
            </div>

            {{-- Contenu : Incidents en cours --}}
            <div x-show="tab === 'en-cours'" class="bg-white border border-gray-200 rounded-xl shadow-sm overflow-hidden">
                @include('services.incidents.partials.table', ['incidents' => $incidentsEnCours])
            </div>

            {{-- Contenu : Incidents classés --}}
            <div x-show="tab === 'archives'" class="bg-white border border-gray-200 rounded-xl shadow-sm overflow-hidden" x-cloak>
                @include('services.incidents.partials.table', ['incidents' => $incidentsClasses])
            </div>
        </div>
    </div>

    {{-- --- LA MODALE --- --}}
    <div id="incidentModal" class="fixed inset-0 z-50 hidden overflow-y-auto" aria-labelledby="modal-title" role="dialog" aria-modal="true">
        <div class="flex items-center justify-center min-h-screen px-4 pt-4 pb-20 text-center sm:block sm:p-0">
            <div class="fixed inset-0 transition-opacity bg-gray-900 bg-opacity-50" onclick="closeModal()"></div>
            <span class="hidden sm:inline-block sm:align-middle sm:h-screen" aria-hidden="true">&#8203;</span>

            <div class="inline-block overflow-hidden text-left align-bottom transition-all transform bg-white rounded-2xl shadow-2xl sm:my-8 sm:align-middle sm:max-w-lg sm:w-full">
                <div class="px-6 py-6 bg-white">
                    <div class="flex justify-between items-start mb-6">
                        <h3 class="text-xl font-bold text-gray-900" id="modal-title">Détails de l'incident</h3>
                        <button onclick="closeModal()" class="text-gray-400 hover:text-gray-600 text-2xl font-bold">&times;</button>
                    </div>
                    
                    <div class="space-y-6">
                        <div class="grid grid-cols-2 gap-4 text-sm border-b border-gray-100 pb-4">
                            <div><p class="text-gray-500">Signalé par</p><p id="modal-user" class="font-bold text-gray-900"></p></div>
                            <div><p class="text-gray-500">Date</p><p id="modal-date" class="font-bold text-gray-900"></p></div>
                        </div>
                        
                        <div>
                            <p class="text-gray-500 text-xs font-bold uppercase">Motif déclaré</p>
                            <p id="modal-motif" class="font-black text-red-600 uppercase tracking-wide"></p>
                        </div>

                        {{-- Section Locataire --}}
                        <div class="bg-gray-50 p-4 rounded-xl border border-gray-200">
                            <p class="text-gray-500 text-xs font-bold uppercase mb-2">Description du locataire :</p>
                            <p id="modal-description" class="text-gray-700 text-sm leading-relaxed whitespace-pre-wrap"></p>
                        </div>

                        {{-- Section Propriétaire (affichée via JS si étape >= 3) --}}
                        <div id="modal-proprio-section" class="bg-orange-50 p-4 rounded-xl border border-orange-200 hidden">
                            <p class="text-orange-800 text-xs font-bold uppercase mb-2">Réponse du propriétaire :</p>
                            <p id="modal-explication-proprio" class="text-gray-700 text-sm leading-relaxed whitespace-pre-wrap"></p>
                        </div>
                    </div>
                </div>

                {{-- Footer dynamique --}}
                <div id="modal-footer" class="px-6 py-4 bg-gray-50 flex flex-col sm:flex-row gap-3">
                    <form id="form-classer" method="POST" class="flex-1">
                        @csrf
                        <button type="submit" class="w-full inline-flex justify-center px-4 py-2.5 text-sm font-bold text-gray-700 bg-white border border-gray-300 rounded-xl hover:bg-gray-100 transition-colors">
                            Classer sans suite
                        </button>
                    </form>

                    <form id="form-action-principale" method="POST" class="flex-1">
                        @csrf
                        <button id="btn-action-text" type="submit" class="w-full inline-flex justify-center px-4 py-2.5 text-sm font-bold text-white rounded-xl shadow-md transition-colors">
                            {{-- Texte injecté par JS --}}
                        </button>
                    </form>
                </div>
            </div>
        </div>
    </div>

    

    <script>
        function openIncidentModal(data) {
            document.getElementById('modal-user').innerText = data.user;
            document.getElementById('modal-date').innerText = data.date;
            document.getElementById('modal-motif').innerText = data.motif;
            document.getElementById('modal-description').innerText = data.description;

            const proprioSection = document.getElementById('modal-proprio-section');
            const footer = document.getElementById('modal-footer');
            const btnActionText = document.getElementById('btn-action-text');
            const formActionPrincipale = document.getElementById('form-action-principale');
            const formLeftAction = document.getElementById('form-classer');
            const btnLeftText = formLeftAction.querySelector('button');

            proprioSection.classList.add('hidden');
            footer.classList.remove('hidden', 'flex');
            btnActionText.classList.remove('bg-blue-600', 'bg-green-600', 'hover:bg-blue-700', 'hover:bg-green-700');

            if (data.etape >= 3 && data.explication) {
                proprioSection.classList.remove('hidden');
                document.getElementById('modal-explication-proprio').innerText = data.explication;
            }

            if (data.estclasse) {
                footer.classList.add('hidden');
            } 
            else if (data.etape == 1) {
                footer.classList.add('flex');
                
                btnLeftText.innerText = "Classer sans suite";
                formLeftAction.action = `/services/incidents/${data.id}/classer`;
                
                btnActionText.innerText = "Demander explication proprio";
                btnActionText.classList.add('bg-blue-600', 'hover:bg-blue-700');
                formActionPrincipale.action = `/services/incidents/${data.id}/valider`;
            } 
            else if (data.etape == 3) {
                footer.classList.add('flex');
                
                btnLeftText.innerText = "Refuser le remboursement";
                formLeftAction.action = `/services/incidents/${data.id}/valider-etape-4`;
                
                btnActionText.innerText = "Enclencher Remboursement";
                btnActionText.classList.add('bg-green-600', 'hover:bg-green-700');
                formActionPrincipale.action = `/services/incidents/${data.id}/rembourser`;
            } 
            else {
                footer.classList.add('hidden');
            }

            document.getElementById('incidentModal').classList.remove('hidden');
            document.body.style.overflow = 'hidden';
        }

        function closeModal() {
            document.getElementById('incidentModal').classList.add('hidden');
            document.body.style.overflow = 'auto';
        }
    </script>
</x-app-layout>