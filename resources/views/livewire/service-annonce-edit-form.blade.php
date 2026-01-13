<div class="space-y-6">
    <div class="bg-white p-6 rounded-2xl border border-gray-200 shadow-sm flex items-center gap-6">
        <div class="w-32 h-32 bg-gray-100 rounded-xl overflow-hidden flex-shrink-0">
             <img src="{{ asset($annonce->photos->first()->lienphoto ?? '') }}" class="w-full h-full object-cover">
        </div>
        <div>
            <h3 class="text-2xl font-bold text-slate-900">{{ $annonce->titreannonce }}</h3>
            <p class="text-slate-500 italic">{{ $annonce->utilisateur->pseudonyme }}</p>
        </div>
    </div>

    <form wire:submit.prevent="save" class="grid grid-cols-1 md:grid-cols-2 gap-8">
        <div class="bg-white p-6 rounded-2xl border border-gray-200 shadow-sm">
            <h3 class="text-lg font-bold mb-6 flex items-center gap-2">🏠 Modifier le type</h3>
            <div class="grid grid-cols-1 gap-3">
                @foreach($types as $type)
                    <label class="flex items-center p-3 rounded-xl border {{ $idtypehebergement == $type->idtypehebergement ? 'border-orange-500 bg-orange-50' : 'border-gray-100' }} cursor-pointer hover:bg-gray-50 transition-colors">
                        <input type="radio" wire:model="idtypehebergement" value="{{ $type->idtypehebergement }}" class="text-orange-600 focus:ring-orange-500">
                        <span class="ml-3 text-sm font-medium text-slate-700">{{ $type->nomtypehebergement }}</span>
                    </label>
                @endforeach
            </div>
        </div>

        <div class="bg-white p-6 rounded-2xl border border-gray-200 shadow-sm">
            <h3 class="text-lg font-bold mb-6 flex items-center gap-2">🔌 Gérer les équipements</h3>
            <div class="grid grid-cols-1 gap-3 max-h-[400px] overflow-y-auto pr-2 custom-scrollbar">
                @foreach($equipements as $equip)
                    <label class="flex items-center p-3 rounded-xl border border-gray-100 cursor-pointer hover:bg-gray-50 transition-colors">
                        <input type="checkbox" wire:model="selectedEquipements" value="{{ $equip->idcommodite }}" class="rounded text-orange-600 focus:ring-orange-500 w-5 h-5">
                        <span class="ml-3 text-sm font-medium text-slate-700">{{ $equip->nomcommodite }}</span>
                    </label>
                @endforeach
            </div>
        </div>

        <div class="md:col-span-2 flex justify-end gap-4 pt-6">
            <a href="{{ route('services.annonce-editor.index') }}" class="px-6 py-3 text-slate-600 font-bold hover:underline">Annuler</a>
            <button type="submit" class="bg-orange-600 hover:bg-orange-700 text-white px-8 py-3 rounded-xl font-bold shadow-lg transition-transform active:scale-95">
                Enregistrer les changements
            </button>
        </div>
    </form>
</div>