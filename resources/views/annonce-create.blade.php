@extends('layouts.app')
@section('content')
    <div class="bg-white p-7 max-w-6xl mx-auto px-6 md:px-12 xl:px-6 pt-32">
        <h1 class="text-2xl font-semibold text-gray-800 mb-6">Déposer une annonce</h1>
        <form id="annonce-form" method="POST" action="{{ route('annonce.store') }}" enctype="multipart/form-data" class="space-y-6">
            @csrf

            <div id="form-errors" class="text-red-600 text-sm space-y-1"></div>

            <h3 class="text-lg font-semibold mt-4 mb-2">Informations générales</h3>
            <div>
                <label for="titre" class="block text-sm font-medium text-gray-700">Quel est le titre de l'annonce ?</label>
                <input type="text" name="titre" id="titre" required value="{{ old('titre') }}"
                    class="mt-1 block w-full border border-gray-300 rounded-md shadow-sm p-2 focus:ring-orange-500 focus:border-orange-500">
                @error('titre')
                    <p class="text-red-600 text-sm mt-1">{{ $message }}</p>
                @enderror
            </div>

            <div>
                <label for="description" class="block text-sm font-medium text-gray-700">Description</label>
                <textarea name="description" id="description" rows="4" required
                    class="mt-1 block w-full border border-gray-300 rounded-md shadow-sm p-2 focus:ring-orange-500 focus:border-orange-500">{{ old('description') }}</textarea>
                @error('description')
                    <p class="text-red-600 text-sm mt-1">{{ $message }}</p>
                @enderror
            </div>

            <h3 class="text-lg font-semibold mt-6 mb-2">Photos</h3>
            <div x-data="imageUploader()">
                <label for="photos" class="block text-sm font-medium text-gray-700">Déposez vos photos</label>
                <input
                    type="file"
                    name="photos[]"
                    id="photos"
                    x-ref="input"
                    accept="image/*"
                    multiple
                    @change="addFiles($event)"
                    class="mt-1 block w-full border border-gray-300 rounded-md shadow-sm p-2 focus:ring-orange-500 focus:border-orange-500"
                >
                <p class="text-xs text-gray-500 mt-1">Formats acceptés: JPG, PNG, WEBP. Vous pouvez sélectionner plusieurs images.</p>
                @error('photos')
                    <p class="text-red-600 text-sm mt-1">{{ $message }}</p>
                @enderror
                @error('photos.*')
                    <p class="text-red-600 text-sm mt-1">{{ $message }}</p>
                @enderror

                <!-- Previews -->
                <div class="mt-4 grid grid-cols-2 sm:grid-cols-3 md:grid-cols-4 gap-4" x-show="files.length > 0">
                    <template x-for="(f, index) in files" :key="f.id">
                        <div class="relative border rounded overflow-hidden">
                            <img :src="f.url" :alt="f.name" class="w-full h-36 object-cover">
                            <button type="button" @click.prevent="remove(index)" class="absolute top-1 right-1 bg-white bg-opacity-90 rounded-full p-1 w-8 h-8 text-red-600 shadow">
                                &times;
                            </button>
                            <div class="p-2 text-xs truncate" x-text="f.name"></div>
                        </div>
                    </template>
                </div>
            </div>

            <div>
                <label for="capacite" class="block text-sm font-medium text-gray-700">Quelle est la capacité totale du bien ?</label>
                <input type="number" name="capacite" id="capacite" min="1" step="1" required value="{{ old('capacite') }}"
                    class="mt-1 block w-full border border-gray-300 rounded-md shadow-sm p-2 focus:ring-orange-500 focus:border-orange-500">
                @error('capacite')
                    <p class="text-red-600 text-sm mt-1">{{ $message }}</p>
                @enderror
            </div>

            <div>
                <label for="nbchambres" class="block text-sm font-medium text-gray-700">Rentrer le nombre de chambres du bien</label>
                <input type="number" name="nbchambres" id="nbchambres" min="0" step="1" required value="{{ old('nbchambres') }}"
                    class="mt-1 block w-full border border-gray-300 rounded-md shadow-sm p-2 focus:ring-orange-500 focus:border-orange-500">
                @error('nbchambres')
                    <p class="text-red-600 text-sm mt-1">{{ $message }}</p>
                @enderror
            </div>
            
            <div>
                <label for="typebien" class="block text-sm font-medium text-gray-700">Choisissez une votre type de bien</label>
                <select name="typebien" id="typebien" required
                    class="mt-1 block w-full border border-gray-300 rounded-md shadow-sm p-2 focus:ring-orange-500 focus:border-orange-500">
                    <option value="">-- Sélectionner un type de bien --</option>
                    @foreach ($typeHebergements as $type)
                        <option value="{{ $type->idtypehebergement }}" {{ old('typebien') == $type->idtypehebergement ? 'selected' : '' }}>{{ $type->nomtypehebergement }}</option>
                    @endforeach
                </select>
                @error('typebien')
                    <p class="text-red-600 text-sm mt-1">{{ $message }}</p>
                @enderror
            </div>

            <h3 class="text-lg font-semibold mt-6 mb-2">Horaires</h3>
            <div class=" flex">
                <div class="mr-4 w-60">
                    <label for="heuredepart" class="block text-sm font-medium text-gray-700">Heure de départ</label>
                    <input type="time" name="heuredepart" id="heuredepart" required value="{{ old('heuredepart') }}"
                        class="mt-1 block w-full border border-gray-300 rounded-md shadow-sm p-2 focus:ring-orange-500 focus:border-orange-500">
                    @error('heuredepart')
                        <p class="text-red-600 text-sm mt-1">{{ $message }}</p>
                    @enderror
                </div>

                <div class="w-60">
                    <label for="heurearrivee" class="block text-sm font-medium text-gray-700">Heure d'arrivée</label>
                    <input type="time" name="heurearrivee" id="heurearrivee" required value="{{ old('heurearrivee') }}"
                        class="mt-1 block w-full border border-gray-300 rounded-md shadow-sm p-2 focus:ring-orange-500 focus:border-orange-500">
                    @error('heurearrivee')
                        <p class="text-red-600 text-sm mt-1">{{ $message }}</p>
                    @enderror
                </div>
            </div>

            @if(isset($categories) && $categories->isNotEmpty())
                <h3 class="text-lg font-semibold mt-6 mb-2">Équipements</h3>
                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                    @foreach ($categories as $categorie)
                        <div class="border border-gray-200 rounded-lg p-4">
                            <p class="font-semibold text-gray-800 mb-2">{{ $categorie->nomcategorie }}</p>
                            <div class="space-y-2">
                                @foreach ($categorie->commodites as $commodite)
                                    <label class="flex items-center space-x-2">
                                        <input type="checkbox" name="commodites[]" value="{{ $commodite->idcommodite }}" class="rounded text-orange-600"
                                            {{ in_array($commodite->idcommodite, old('commodites', [])) ? 'checked' : '' }}>
                                        <span class="text-sm text-gray-700">{{ $commodite->nomcommodite }}</span>
                                    </label>
                                @endforeach
                            </div>
                        </div>
                    @endforeach
                </div>
                @error('commodites')
                    <p class="text-red-600 text-sm mt-1">{{ $message }}</p>
                @enderror
                @error('commodites.*')
                    <p class="text-red-600 text-sm mt-1">{{ $message }}</p>
                @enderror
            @endif

            <div>
                <label for="possibilitefumeur" class="block text-sm font-medium text-gray-700">Fumeur autorisé</label>
                <select name="possibilitefumeur" id="possibilitefumeur" required
                    class="mt-1 block w-full border border-gray-300 rounded-md shadow-sm p-2 focus:ring-orange-500 focus:border-orange-500">
                    <option value="">-- Sélectionner une option --</option>
                    <option value="1" {{ old('possibilitefumeur') === '1' ? 'selected' : '' }}>Oui</option>
                    <option value="0" {{ old('possibilitefumeur') === '0' ? 'selected' : '' }}>Non</option>
                </select>
                @error('possibilitefumeur')
                    <p class="text-red-600 text-sm mt-1">{{ $message }}</p>
                @enderror
            </div>

            {{-- <h3 class="text-lg font-semibold mt-6 mb-2">Localisation</h3>
            <div>
                <label for="adresse" class="block text-sm font-medium text-gray-700">Quel est l'adresse de votre bien ?</label>
                <input type="text" name="adresse" id="adresse" required
                    class="mt-1 block w-full border border-gray-300 rounded-md shadow-sm p-2 focus:ring-orange-500 focus:border-orange-500">
                @error('adresse')
                    <p class="text-red-600 text-sm mt-1">{{ $message }}</p>
                @enderror
            </div>

            <h3 class="text-lg font-semibold mt-6 mb-2">Caractéristiques</h3>

            <h3 class="text-lg font-semibold mt-6 mb-2">Tarifs & conditions</h3>
            <div>
                <label for="montantacompte" class="block text-sm font-medium text-gray-700">Montant de l'acompte (€)</label>
                <input type="number" name="montantacompte" id="montantacompte" min="0" step="0.01" required
                    class="mt-1 block w-full border border-gray-300 rounded-md shadow-sm p-2 focus:ring-orange-500 focus:border-orange-500">
                @error('montantacompte')
                    <p class="text-red-600 text-sm mt-1">{{ $message }}</p>
                @enderror
            </div>

            <div>
                <label for="pourcentageacompte" class="block text-sm font-medium text-gray-700">Pourcentage d'acompte (%)</label>
                <input type="number" name="pourcentageacompte" id="pourcentageacompte" min="0" max="100" step="1" required
                    class="mt-1 block w-full border border-gray-300 rounded-md shadow-sm p-2 focus:ring-orange-500 focus:border-orange-500">
                @error('pourcentageacompte')
                    <p class="text-red-600 text-sm mt-1">{{ $message }}</p>
                @enderror
            </div>

            <div>
                <label for="prixnuitee" class="block text-sm font-medium text-gray-700">Prix par nuitée (€)</label>
                <input type="number" name="prixnuitee" id="prixnuitee" min="0" step="0.01" required
                    class="mt-1 block w-full border border-gray-300 rounded-md shadow-sm p-2 focus:ring-orange-500 focus:border-orange-500">
                @error('prixnuitee')
                    <p class="text-red-600 text-sm mt-1">{{ $message }}</p>
                @enderror
            </div>

            <div>
                <label for="minimumnuitee" class="block text-sm font-medium text-gray-700">Nombre minimum de nuitées</label>
                <input type="number" name="minimumnuitee" id="minimumnuitee" min="1" step="1" required
                    class="mt-1 block w-full border border-gray-300 rounded-md shadow-sm p-2 focus:ring-orange-500 focus:border-orange-500">
                @error('minimumnuitee')
                    <p class="text-red-600 text-sm mt-1">{{ $message }}</p>
                @enderror
            </div>

            <h3 class="text-lg font-semibold mt-6 mb-2">Règles</h3>
            <div>
                <label for="nombreanimeauxmax" class="block text-sm font-medium text-gray-700">Nombre maximum d'animaux</label>
                <input type="number" name="nombreanimeauxmax" id="nombreanimeauxmax" min="0" step="1" required
                    class="mt-1 block w-full border border-gray-300 rounded-md shadow-sm p-2 focus:ring-orange-500 focus:border-orange-500">
                @error('nombreanimeauxmax')
                    <p class="text-red-600 text-sm mt-1">{{ $message }}</p>
                @enderror
            </div>

            <div>
                <label for="nombrebebesmax" class="block text-sm font-medium text-gray-700">Nombre maximum de bébés</label>
                <input type="number" name="nombrebebesmax" id="nombrebebesmax" min="0" step="1" required
                    class="mt-1 block w-full border border-gray-300 rounded-md shadow-sm p-2 focus:ring-orange-500 focus:border-orange-500">
                @error('nombrebebesmax')
                    <p class="text-red-600 text-sm mt-1">{{ $message }}</p>
                @enderror
            </div>
             --}}

            <div>
                <button type="submit"
                    class="px-4 py-2 bg-orange-600 text-white rounded-md hover:bg-orange-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-orange-500">
                    Publier l'annonce
                </button>
            </div>
        </form>
    </div>
    <script>
        function imageUploader() {
            return {
                files: [],
                addFiles(event) {
                    const selected = Array.from(event.target.files || []);
                    selected.forEach(file => {
                        if (!file.type.startsWith('image/')) return;
                        this.files.push({
                            id: Date.now().toString(36) + Math.random().toString(36).slice(2),
                            file: file,
                            url: URL.createObjectURL(file),
                            name: file.name,
                        });
                    });
                    this.updateInputFiles();
                },
                remove(index) {
                    URL.revokeObjectURL(this.files[index].url);
                    this.files.splice(index, 1);
                    this.updateInputFiles();
                },
                updateInputFiles() {
                    try {
                        const dt = new DataTransfer();
                        this.files.forEach(f => dt.items.add(f.file));
                        this.$refs.input.files = dt.files;
                    } catch (e) {
                        console.warn("DataTransfer n'est pas pris en charge, la suppression de l'aperçu n'actualisera pas les fichiers de l'input", e);
                    }
                }
            }
        }
    </script>
@endsection

@push('scripts')
@endpush