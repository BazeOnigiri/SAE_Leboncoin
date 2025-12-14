@extends('layouts.app')
@section('content')
    <div class="bg-white p-7 max-w-6xl mx-auto px-6 md:px-12 xl:px-6 pt-32">
        <h1 class="text-2xl font-semibold text-gray-800 mb-6">Déposer une annonce</h1>
        <form id="annonce-form" method="POST" action="{{ route('annonce.store') }}" enctype="multipart/form-data" class="space-y-6"
            x-data="{
                // --- LOGIQUE ADRESSE (API GOUV) ---
                query: '',
                suggestions: [],
                showSuggestions: false,
                showCityModal: false,
                citiesByCp: [],
                modalCp: '',
                selectedCityRadio: '',

                searchAddress() {
                    if (this.query.length < 3) { this.suggestions = []; return; }
                    fetch(`https://api-adresse.data.gouv.fr/search/?q=${this.query}&limit=5&autocomplete=1`)
                        .then(res => res.json())
                        .then(data => { this.suggestions = data.features; this.showSuggestions = true; });
                },

                selectAddress(feature) {
                    const props = feature.properties;
                    // Remplissage visuel
                    document.getElementById('street_number_display').value = props.housenumber || '';
                    document.getElementById('route_display').value = props.street || props.name;
                    document.getElementById('postal_code_display').value = props.postcode;
                    document.getElementById('locality_display').value = props.city;

                    // Remplissage caché (Backend)
                    document.getElementById('numerorue').value = props.housenumber || 1;
                    document.getElementById('nomrue').value = props.street || props.name;
                    document.getElementById('codepostal').value = props.postcode;
                    document.getElementById('ville').value = props.city;

                    this.query = '';
                    this.showSuggestions = false;
                },

                async openCityChooser() {
                    const cp = document.getElementById('postal_code_display').value;
                    const currentCity = document.getElementById('locality_display').value;
                    if (!cp || cp.length !== 5) { alert('Veuillez d\'abord rechercher une adresse.'); return; }

                    this.modalCp = cp;
                    this.citiesByCp = [];
                    this.showCityModal = true;

                    try {
                        const res = await fetch(`https://geo.api.gouv.fr/communes?codePostal=${cp}&fields=nom&format=json`);
                        this.citiesByCp = await res.json();
                        if (this.citiesByCp.length > 0) {
                            const found = this.citiesByCp.find(c => c.nom.toUpperCase() === currentCity.toUpperCase());
                            this.selectedCityRadio = found ? found.nom : this.citiesByCp[0].nom;
                        }
                    } catch (e) { alert('Erreur chargement villes'); }
                },

                validateCityChoice() {
                    if (!this.selectedCityRadio) return;
                    document.getElementById('locality_display').value = this.selectedCityRadio;
                    document.getElementById('ville').value = this.selectedCityRadio;
                    this.showCityModal = false;
                }
            }"
        >
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

            <h3 class="text-lg font-semibold mt-6 mb-2">Localisation du bien</h3>
            <div class="bg-gray-50 p-4 rounded-lg border border-gray-200">
                {{-- Champ de recherche --}}
                <div class="mb-4 relative">
                    <label class="block text-sm font-medium text-gray-700 mb-1">Rechercher l'adresse *</label>
                    <input 
                        type="text" 
                        x-model="query"
                        @input.debounce.300ms="searchAddress()"
                        placeholder="Tapez l'adresse du logement..." 
                        class="mt-1 block w-full border border-gray-300 rounded-md shadow-sm p-2 focus:ring-orange-500 focus:border-orange-500" 
                        autocomplete="off"
                    >
                    
                    {{-- Suggestions --}}
                    <div x-show="showSuggestions && suggestions.length > 0" @click.away="showSuggestions = false" class="absolute z-10 w-full bg-white border border-gray-200 rounded-lg shadow-lg mt-1 max-h-60 overflow-y-auto">
                        <template x-for="feature in suggestions" :key="feature.properties.id">
                            <div @click="selectAddress(feature)" class="px-4 py-2 hover:bg-orange-50 cursor-pointer border-b border-gray-100 last:border-0 text-sm text-gray-700">
                                <span class="font-bold" x-text="feature.properties.label"></span>
                            </div>
                        </template>
                    </div>
                </div>

                {{-- Champs grisés --}}
                <div class="grid grid-cols-4 gap-4 mb-4">
                    <div class="col-span-1">
                        <label class="block text-xs font-bold text-gray-500 mb-1">N°</label>
                        <input id="street_number_display" type="text" class="block w-full border-gray-300 rounded-md bg-gray-100 text-gray-600" readonly>
                    </div>
                    <div class="col-span-3">
                        <label class="block text-xs font-bold text-gray-500 mb-1">Rue</label>
                        <input id="route_display" type="text" class="block w-full border-gray-300 rounded-md bg-gray-100 text-gray-600" readonly>
                    </div>
                </div>

                <div class="grid grid-cols-2 gap-4">
                    <div>
                        <label class="block text-xs font-bold text-gray-500 mb-1">Code Postal</label>
                        <input id="postal_code_display" type="text" class="block w-full border-gray-300 rounded-md bg-gray-100 text-gray-600" readonly>
                    </div>
                    <div>
                        <label class="block text-xs font-bold text-gray-500 mb-1">Ville</label>
                        <div class="flex gap-2">
                            <input id="locality_display" type="text" class="block w-full border-gray-300 rounded-md bg-gray-100 text-gray-600" readonly>
                            <button type="button" @click="openCityChooser()" class="px-3 py-2 text-xs font-bold bg-white border border-gray-300 rounded-md hover:bg-gray-50">
                                Choisir
                            </button>
                        </div>
                    </div>
                </div>

                {{-- Inputs Cachés pour le backend --}}
                <input type="hidden" name="numerorue" id="numerorue">
                <input type="hidden" name="nomrue" id="nomrue">
                <input type="hidden" name="codepostal" id="codepostal">
                <input type="hidden" name="ville" id="ville">
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
                        @if ($categorie->commodites->isNotEmpty())
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
                        @endif
                    @endforeach
                </div>
                @error('commodites') 
                <p class="text-red-600 text-sm mt-1">{{ $message }}</p> 
                @enderror
            @endif

            <h3 class="text-lg font-semibold mt-6 mb-2">Tarifs</h3>
            <div>
                <label for="prixnuitee" class="block text-sm font-medium text-gray-700">Prix par nuitée (€)</label>
                <input type="number" name="prixnuitee" id="prixnuitee" min="0" step="0.01" required value="{{ old('prixnuitee') }}"
                    class="mt-1 block w-full border border-gray-300 rounded-md shadow-sm p-2 focus:ring-orange-500 focus:border-orange-500">
                @error('prixnuitee') 
                <p class="text-red-600 text-sm mt-1">{{ $message }}</p> 
                @enderror
            </div>

            <div>
                <label for="pourcentageacompte" class="block text-sm font-medium text-gray-700">Pourcentage d'acompte (%)</label>
                <input type="number" name="pourcentageacompte" id="pourcentageacompte" min="0" max="100" step="1" required value="{{ old('pourcentageacompte') }}"
                    class="mt-1 block w-full border border-gray-300 rounded-md shadow-sm p-2 focus:ring-orange-500 focus:border-orange-500">
                <p class="text-xs text-gray-500 mt-1">L'acompte sera calculé sur le prix de la nuitée lors de la réservation</p>
                @error('pourcentageacompte') 
                <p class="text-red-600 text-sm mt-1">{{ $message }}</p> 
                @enderror
            </div>

            <div>
                <label for="minimumnuitee" class="block text-sm font-medium text-gray-700">Nombre minimum de nuitées</label>
                <input type="number" name="minimumnuitee" id="minimumnuitee" min="1" step="1" required value="{{ old('minimumnuitee') }}"
                    class="mt-1 block w-full border border-gray-300 rounded-md shadow-sm p-2 focus:ring-orange-500 focus:border-orange-500">
                @error('minimumnuitee') 
                <p class="text-red-600 text-sm mt-1">{{ $message }}</p> 
                @enderror
            </div>
            
            <h3 class="text-lg font-semibold mt-6 mb-2">Règles</h3>
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


            <div>
                <button type="submit"
                    class="px-4 py-2 bg-orange-600 text-white rounded-md hover:bg-orange-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-orange-500">
                    Publier l'annonce
                </button>
            </div>

            {{--MODALE DE CHOIX DE VILLE (DANS LE FORM, GÉRÉE PAR ALPINE) --}}
            <div x-show="showCityModal" x-cloak class="fixed inset-0 bg-black/40 flex items-center justify-center z-50">
                <div @click.away="showCityModal = false" class="bg-white rounded-2xl shadow-xl max-w-md w-full mx-4 p-6">
                    <h3 class="text-lg font-bold text-gray-900 mb-2">Choisir une ville</h3>
                    <p class="text-sm text-gray-600 mb-4">Plusieurs villes existent pour <span class="font-bold" x-text="modalCp"></span> :</p>
                    <div class="space-y-2 max-h-60 overflow-y-auto mb-4">
                        <template x-for="city in citiesByCp" :key="city.code">
                            <label class="flex items-center gap-2 p-2 hover:bg-gray-50 rounded cursor-pointer">
                                <input type="radio" name="city_select" :value="city.nom" x-model="selectedCityRadio" class="text-orange-600 focus:ring-orange-600">
                                <span x-text="city.nom"></span>
                            </label>
                        </template>
                    </div>
                    <div class="flex justify-end gap-2">
                        <button type="button" @click="showCityModal = false" class="px-4 py-2 border rounded-lg hover:bg-gray-100">Annuler</button>
                        <button type="button" @click="validateCityChoice()" class="px-4 py-2 bg-orange-600 text-white font-bold rounded-lg hover:bg-orange-700">Valider</button>
                    </div>
                </div>
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