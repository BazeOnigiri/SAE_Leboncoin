@extends('layouts.app')
@section('content')
    <div class="bg-white p-7 max-w-6xl mx-auto px-6 md:px-12 xl:px-6 pt-32">
        @if (session('success'))
            <div class="mb-6 p-4 bg-green-50 border-l-4 border-green-500 text-green-800 rounded-md">
                {{ session('success') }}
            </div>
        @endif
            
        @if ($errors->has('error'))
            <div class="mb-6 p-4 bg-red-50 border-l-4 border-red-500 text-red-800 rounded-md">
                {{ $errors->first('error') }}
            </div>
        @endif

        <form method="POST" action="{{ route('cni.store')}}"  enctype="multipart/form-data" class="space-y-6">
            @csrf

            <h1 class="text-2xl font-semibold text-gray-800">Déposer une copie de votre CNI</h1>
            <p class="text-sm text-gray-500">Nous utilisons ces informations uniquement pour vérification d'identité. Formats acceptés : JPG, PNG. Taille maximale : 5 Mo par image.</p>

            

            <div class="grid grid-cols-1 md:grid-cols-2 gap-6 items-start">
                <div>
                    <label class="block text-sm font-medium text-gray-700">Photo recto de la CNI</label>
                    <div class="mt-2 flex items-center space-x-4">
                        <label class="flex items-center justify-center w-full h-40 border-2 border-dashed border-gray-200 rounded-md cursor-pointer bg-white hover:border-orange-400">
                            <input id="recto" name="recto" type="file" accept="image/*" class="hidden" required onchange="previewImage(event, 'preview-recto')" />
                            <div class="text-center">
                                <svg class="mx-auto h-8 w-8 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 16l-4-4m0 0l4-4m-4 4h18"/></svg>
                                <p class="text-sm text-gray-600">Cliquez pour choisir ou glisser-déposer</p>
                            </div>
                        </label>
                    </div>
                    @error('recto') <p class="text-red-600 text-sm mt-1">{{ $message }}</p> @enderror
                    <div id="preview-recto" class="mt-3"></div>
                </div>

                <div>
                    <label class="block text-sm font-medium text-gray-700">Photo verso de la CNI</label>
                    <div class="mt-2 flex items-center space-x-4">
                        <label class="flex items-center justify-center w-full h-40 border-2 border-dashed border-gray-200 rounded-md cursor-pointer bg-white hover:border-orange-400">
                            <input id="verso" name="verso" type="file" accept="image/*" class="hidden" required onchange="previewImage(event, 'preview-verso')" />
                            <div class="text-center">
                                <svg class="mx-auto h-8 w-8 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 16l-4-4m0 0l4-4m-4 4h18"/></svg>
                                <p class="text-sm text-gray-600">Cliquez pour choisir ou glisser-déposer</p>
                            </div>
                        </label>
                    </div>
                    @error('verso') <p class="text-red-600 text-sm mt-1">{{ $message }}</p> @enderror
                    <div id="preview-verso" class="mt-3"></div>
                </div>
            </div>

            <div class="bg-yellow-50 border-l-4 border-yellow-400 p-4 rounded-md">
                <p class="text-sm text-yellow-800">Conseils :</p>
                <ul class="list-disc pl-5 text-sm text-gray-700">
                    <li>Assurez-vous que les bords et les textes de la carte sont lisibles.</li>
                    <li>Pas de reflets, ni flou. Photo prise à la lumière du jour si possible.</li>
                    <li>Les images doivent être en couleur et contenir uniquement la carte.</li>
                </ul>
            </div>

            <div class="flex items-start space-x-3">
                <input id="consent" name="consent" type="checkbox" {{ old('consent') ? 'checked' : '' }} class="mt-1 h-4 w-4 text-orange-600 border-gray-300 rounded" required>
                <label for="consent" class="text-sm text-gray-700">J'accepte que ces documents soient utilisés pour vérifier mon identité.</label>
            </div>
            @error('consent') <p class="text-red-600 text-sm mt-1">{{ $message }}</p> @enderror

            <div class="flex justify-end">
                <button type="submit" class="inline-flex items-center px-6 py-2 bg-orange-500 hover:bg-orange-600 text-white font-medium rounded-md shadow-sm focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-orange-400">
                    Déposer ma CNI
                </button>
            </div>
        </form>

        <script>
        function previewImage(event, containerId) {
            const file = event.target.files[0];
            const container = document.getElementById(containerId);
            container.innerHTML = '';
            if (!file) return;

            if (!file.type.startsWith('image/')) {
                container.innerHTML = '<p class="text-red-600 text-sm">Fichier non valide. Veuillez sélectionner une image.</p>';
                event.target.value = '';
                return;
            }

            if (file.size > 5 * 1024 * 1024) {
                container.innerHTML = '<p class="text-red-600 text-sm">Fichier trop volumineux (max 5 Mo).</p>';
                event.target.value = '';
                return;
            }

            const img = document.createElement('img');
            img.className = 'h-32 rounded-md border';
            img.alt = 'Aperçu';
            img.src = URL.createObjectURL(file);
            container.appendChild(img);
        }
        </script>
    </div>
@endsection