@extends('layouts.app')
@section('content')
    @php
        use Carbon\Carbon;
        
        $dateArrivee = old('date_debut', $arrivee ?? null);
        $dateDepart = old('date_fin', $depart ?? null);
        
        $nbNuits = 0;
        $formattedArrivee = '-';
        $formattedDepart = '-';

        if($dateArrivee && $dateDepart) {
            try {
                if (strpos($dateArrivee, '/') !== false) {
                    $start = Carbon::createFromFormat('d/m/Y', $dateArrivee);
                } else {
                    $start = Carbon::parse($dateArrivee);
                }
                
                if (strpos($dateDepart, '/') !== false) {
                    $end = Carbon::createFromFormat('d/m/Y', $dateDepart);
                } else {
                    $end = Carbon::parse($dateDepart);
                }
                
                if($end->gt($start)) {
                    $nbNuits = $start->diffInDays($end);
                    $formattedArrivee = $start->format('d/m/Y');
                    $formattedDepart = $end->format('d/m/Y');
                }
            } catch (\Exception $e) {
            }
        }
    @endphp
    <div class="bg-white min-h-screen pt-32 pb-12">
        <div class="max-w-6xl mx-auto px-6 md:px-12 xl:px-6">

            <h1 class="text-3xl font-bold text-slate-900 mb-2">Votre réservation</h1>
            
            <div class="flex items-center gap-2 mb-8 text-sm text-slate-600">
                <span>Vous bénéficiez de la</span>
                <span class="flex items-center gap-1 font-bold text-[#EA580C]">
                    <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor" class="w-4 h-4">
                        <path fill-rule="evenodd" d="M10.339 2.237a.532.532 0 00-.678 0 11.947 11.947 0 01-7.078 2.75.5.5 0 00-.479.425A12.11 12.11 0 002 7c0 5.163 3.26 9.564 7.834 11.257a.48.48 0 00.332 0C14.74 16.564 18 12.163 18 7c0-.538-.035-1.067-.104-1.588a.5.5 0 00-.479-.425 11.947 11.947 0 01-7.078-2.75zm-2.5 6.75a.75.75 0 10-1.5 0 .75.75 0 001.5 0zm5.5 0h-2.5a.75.75 0 000 1.5h2.5a.75.75 0 000-1.5z" clip-rule="evenodd" />
                    </svg>
                    Protection Voyageur
                </span>
            </div>

            <form id="reservation-form" action="{{ route('reservation.store', ['id' => $annonce->idannonce]) }}" method="POST" data-price="{{ $annonce->prixnuitee }}" data-nights="{{ $nbNuits > 0 ? $nbNuits : 1 }}">
                @csrf
                
                <div class="grid grid-cols-1 lg:grid-cols-3 gap-12">

                    <div class="lg:col-span-2 space-y-10">

                        <section>
    <h2 class="text-xl font-bold text-slate-900 mb-4">Vos dates de séjour</h2>

<p class="text-slate-600 mb-4">
    @if($nbNuits > 0)
        <span class="font-bold text-slate-800">{{ $nbNuits }} nuits</span> 
    @else
        <span class="text-red-500 font-bold">Durée à définir</span>
    @endif
    à {{ $annonce->adresse->ville->nomville ?? 'Ville' }}
</p>

<div class="grid grid-cols-2 gap-8 border-b border-gray-200 pb-8">
    <div>
        <span class="block text-sm font-bold text-slate-700">Arrivée</span>
        <span class="text-lg text-slate-900">{{ $formattedArrivee }}</span>
        <input type="hidden" name="date_debut" value="{{ $dateArrivee }}">
    </div>
    <div>
        <span class="block text-sm font-bold text-slate-700">Départ</span>
        <span class="text-lg text-slate-900">{{ $formattedDepart }}</span>
        <input type="hidden" name="date_fin" value="{{ $dateDepart }}">
    </div>
</div>
    @if($nbNuits === 0)
        <div class="mt-4 p-4 bg-red-50 text-red-700 rounded-lg text-sm">
            Attention : Les dates semblent manquantes ou incorrectes. Veuillez retourner sur l'annonce pour sélectionner vos dates.
        </div>
    @endif
</section>

                        <section class="border-b border-gray-200 pb-8">
                            <h2 class="text-xl font-bold text-slate-900 mb-6">Nombre de voyageurs</h2>

                            <div class="flex justify-between items-center mb-4">
                                <div>
                                    <div class="font-medium text-slate-900">Adultes</div>
                                    <div class="text-sm text-slate-500">18 ans et plus</div>
                                </div>
                                <div class="flex items-center gap-3">
                                    <button type="button" class="btn-minus w-8 h-8 rounded-full border border-gray-300 flex items-center justify-center text-gray-500 hover:border-slate-800 hover:text-slate-800 disabled:opacity-50" data-target="adults">-</button>
                                    <span id="count-adults" class="w-4 text-center font-medium">1</span>
                                    <button type="button" class="btn-plus w-8 h-8 rounded-full border border-gray-300 flex items-center justify-center text-gray-500 hover:border-slate-800 hover:text-slate-800" data-target="adults">+</button>
                                    <input type="hidden" name="adults" id="input-adults" value="1">
                                </div>
                            </div>

                            <div class="flex justify-between items-center mb-4">
                                <div>
                                    <div class="font-medium text-slate-900">Enfants</div>
                                    <div class="text-sm text-slate-500">De 2 à 17 ans</div>
                                </div>
                                <div class="flex items-center gap-3">
                                    <button type="button" class="btn-minus w-8 h-8 rounded-full border border-gray-300 flex items-center justify-center text-gray-500 hover:border-slate-800 hover:text-slate-800" data-target="children">-</button>
                                    <span id="count-children" class="w-4 text-center font-medium">0</span>
                                    <button type="button" class="btn-plus w-8 h-8 rounded-full border border-gray-300 flex items-center justify-center text-gray-500 hover:border-slate-800 hover:text-slate-800" data-target="children">+</button>
                                    <input type="hidden" name="children" id="input-children" value="0">
                                </div>
                            </div>

                            <div class="flex justify-between items-center mb-4">
                                <div>
                                    <div class="font-medium text-slate-900">Bébés</div>
                                    <div class="text-sm text-slate-500">Moins de 3 ans</div>
                                </div>
                                <div class="flex items-center gap-3">
                                    <button type="button" class="btn-minus w-8 h-8 rounded-full border border-gray-300 flex items-center justify-center text-gray-500 hover:border-slate-800 hover:text-slate-800" data-target="babies">-</button>
                                    <span id="count-babies" class="w-4 text-center font-medium">0</span>
                                    <button type="button" class="btn-plus w-8 h-8 rounded-full border border-gray-300 flex items-center justify-center text-gray-500 hover:border-slate-800 hover:text-slate-800" data-target="babies">+</button>
                                    <input type="hidden" name="babies" id="input-babies" value="0">
                                </div>
                            </div>

                            <p class="text-xs text-gray-500 mt-2">
                                Capacité maximale : <span id="max-capacity">{{ $annonce->capacite ?? 2 }}</span> personnes | Maximum <span id="max-babies">6</span> bébés | Au moins 1 adulte requis
                            </p>

                            <div class="flex justify-between items-center opacity-50">
                                <div>
                                    <div class="font-medium text-slate-900">Animaux</div>
                                    <div class="text-sm text-slate-500 flex items-center gap-1">
                                        Non acceptés 
                                        <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="w-4 h-4"><path stroke-linecap="round" stroke-linejoin="round" d="M9.879 7.519c1.171-1.025 3.071-1.025 4.242 0 1.172 1.025 1.172 2.687 0 3.712-.203.179-.43.326-.67.442-.745.361-1.45.999-1.45 1.827v.75M21 12a9 9 0 11-18 0 9 9 0 0118 0zm-9 5.25h.008v.008H12v-.008z" /></svg>
                                    </div>
                                </div>
                                <div class="flex items-center gap-3">
                                    <div class="w-8 h-8 rounded-full border border-gray-200 bg-gray-100 flex items-center justify-center text-gray-300">-</div>
                                    <span class="w-4 text-center font-medium text-gray-400">0</span>
                                    <div class="w-8 h-8 rounded-full border border-gray-200 bg-gray-100 flex items-center justify-center text-gray-300">+</div>
                                </div>
                            </div>
                        </section>




                        <section class="border-b border-gray-200 pb-8">
                                <h2 class="text-xl font-bold text-slate-900 mb-6">Vos informations</h2>
                                <p class="text-xs text-gray-500 mb-4">* Champs obligatoires</p>

                                <div class="grid grid-cols-1 md:grid-cols-2 gap-4 mb-4">

                                    <div>
                                        <label class="block text-sm font-medium text-slate-700 mb-1">Prénom *</label>
                                        <input type="text" 
                                                name="prenomutilisateur" 
                                                value="{{ old('prenom', Auth::user()->prenom ?? '') }}" 
                                                class="w-full border-gray-300 rounded-lg shadow-sm focus:border-orange-500 focus:ring-orange-500 h-12"
                                                required>
                                    </div>

                                    <div>
                                        <label class="block text-sm font-medium text-slate-700 mb-1">Nom *</label>
                                        <input type="text" 
                                                name="nomutilisateur" 
                                                value="{{ old('nom', Auth::user()->nom ?? '') }}" 
                                                class="w-full border-gray-300 rounded-lg shadow-sm focus:border-orange-500 focus:ring-orange-500 h-12"
                                                required>
                                    </div>
                                </div>

                                <div class="mb-2">
                                    <label class="block text-sm font-medium text-slate-700 mb-1">Numéro de téléphone</label>
                                    <input type="text" 
                                            name="telephoneutilisateur" 
                                            value="{{ old('telephone', Auth::user()->telephone ?? '') }}" 
                                            class="w-full border-gray-300 rounded-lg shadow-sm focus:border-orange-500 focus:ring-orange-500 h-12" 
                                            placeholder="06 12 34 56 78">
                                </div>
                                <p class="text-xs text-gray-500">Votre numéro de téléphone sera partagé à l'hôte une fois votre demande de réservation acceptée</p>
                            </section>











                            
                        <section class="pb-8">
                            <h2 class="text-xl font-bold text-slate-900 mb-2">Envoyer un message à {{ $annonce->utilisateur->pseudonyme ?? 'l\'hôte' }}</h2>
                            
                            <div class="bg-orange-100 text-orange-800 text-xs font-bold px-2 py-1 rounded w-fit mb-4">
                                Disponibilités non confirmées
                            </div>

                            <div class="relative">
                                <textarea name="message" rows="4" class="w-full border-gray-300 rounded-lg shadow-sm focus:border-orange-500 focus:ring-orange-500 p-4 text-sm" placeholder="Dites quelques mots sur vous à votre hôte, votre heure d'arrivée et ce qui vous amène dans la région."></textarea>
                                <div class="text-right text-xs text-gray-400 mt-1">0/2500</div>
                            </div>
                        </section>

                        <section class="bg-gray-50 p-6 rounded-xl">
                            <h3 class="flex items-center gap-2 font-bold text-slate-800 mb-2">
                                <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor" class="w-5 h-5 text-[#EA580C]">
                                    <path fill-rule="evenodd" d="M10.339 2.237a.532.532 0 00-.678 0 11.947 11.947 0 01-7.078 2.75.5.5 0 00-.479.425A12.11 12.11 0 002 7c0 5.163 3.26 9.564 7.834 11.257a.48.48 0 00.332 0C14.74 16.564 18 12.163 18 7c0-.538-.035-1.067-.104-1.588a.5.5 0 00-.479-.425 11.947 11.947 0 01-7.078-2.75zm-2.5 6.75a.75.75 0 10-1.5 0 .75.75 0 001.5 0zm5.5 0h-2.5a.75.75 0 000 1.5h2.5a.75.75 0 000-1.5z" clip-rule="evenodd" />
                                </svg>
                                Protection Voyageur
                            </h3>
                            <p class="text-sm text-slate-600 mb-3">Grâce au paiement en ligne, vous bénéficiez de la protection voyageur :</p>
                            <ul class="text-sm text-slate-600 space-y-2 mb-4">
                                <li class="flex items-start gap-2">
                                    <svg class="w-4 h-4 text-slate-800 mt-0.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path></svg>
                                    <span><strong>Annulez gratuitement</strong> jusqu'à 30 jours avant le début du séjour</span>
                                </li>
                                <li class="flex items-start gap-2">
                                    <svg class="w-4 h-4 text-slate-800 mt-0.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path></svg>
                                    <span><strong>Aucun montant</strong> n'est débité avant l'acceptation de la réservation</span>
                                </li>
                                <li class="flex items-start gap-2">
                                    <svg class="w-4 h-4 text-slate-800 mt-0.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path></svg>
                                    <span><strong>Assurance incluse</strong> en cas de problème à votre arrivée</span>
                                </li>
                            </ul>
                            <a href="#" class="text-sm font-semibold underline text-slate-900 mb-6 block">En savoir plus →</a>

                            <div class="flex items-start gap-3 mb-6">
                                <input id="cgv" type="checkbox" required class="mt-1 w-5 h-5 text-orange-600 border-gray-300 rounded focus:ring-orange-500">
                                <label for="cgv" class="text-sm text-slate-600">
                                    En validant, j'accepte les <a href="#" class="underline font-bold text-slate-900">conditions d'utilisation</a> et les <a href="#" class="underline font-bold text-slate-900">CGV</a>, et certifie que mes prénoms et mon nom sont conformes à ceux de mon état civil.
                                </label>
                            </div>

                            <button type="submit" class="w-full sm:w-auto bg-[#f56b2a] hover:bg-[#e05a1a] text-white font-bold py-3 px-8 rounded-full transition shadow-md text-lg">
                                Payer et valider ma réservation
                            </button>
                        </section>

                    </div>

                    <div class="lg:col-span-1">
                        <div class="sticky top-24 space-y-6">
                            
                            <div class="flex gap-4 items-start">
                                <img src="{{ $annonce->photos->first()->lienphoto ?? 'https://via.placeholder.com/100' }}" alt="Logement" class="w-24 h-24 object-cover rounded-lg border border-gray-200">
                                <div>
                                    <h3 class="font-bold text-slate-900 line-clamp-2 leading-tight mb-1">{{ $annonce->titreannonce }}</h3>
                                    <p class="text-sm text-slate-500">{{ $annonce->capacite ?? 2 }} personnes / {{ $annonce->nbchambres ?? 1 }} chambre</p>
                                    <p class="text-sm text-slate-500">{{ $annonce->adresse->ville->nomville ?? 'Ville' }}</p>
                                </div>
                            </div>

                            <hr class="border-gray-200">

                            <div class="flex items-center gap-3">
                                <div class="w-10 h-10 rounded-full bg-[#EA580C] text-white flex items-center justify-center text-sm font-bold">
                                    {{ strtoupper(substr($annonce->utilisateur->pseudonyme ?? 'U', 0, 1)) }}
                                </div>




                                <a class="underline font-bold" href="{{ route('user.profile', ['id' => $annonce->utilisateur->idutilisateur]) }}">
                                    {{ $annonce->utilisateur->pseudonyme ?? 'Utilisateur inconnu' }}
                                </a>
                            </div>

                            <hr class="border-gray-200">

                            @php
                                $pricePerNight = $annonce->prixnuitee;
                                $nightsToCalculate = ($nbNuits > 0) ? $nbNuits : 1;
                                $totalPersons = 1;
                                $totalRent = $pricePerNight * $nightsToCalculate;
                                $serviceFee = $totalRent * 0.14;
                                $touristTax = 4.00 * $nightsToCalculate * $totalPersons;
                                $total = $totalRent + $serviceFee + $touristTax;
                                $payNow = $serviceFee + ($totalRent * 0.35);
                                $payLater = $total - $payNow;
                            @endphp

                            <div class="p-4 bg-white rounded-lg shadow-lg">

    <h3 class="font-bold text-lg text-slate-900 mb-6">Récapitulatif du paiement</h3>

    @if($nbNuits == 0)
        <div class="bg-red-100 text-red-700 px-4 py-3 rounded text-sm mb-6">
            Veuillez sélectionner vos dates sur l'annonce.
        </div>
    @endif

    <div class="space-y-4 text-sm text-slate-600">
        <div class="flex justify-between">
            <span>Montant de la location</span>
            <span class="ml-auto" data-update-rent>{{ number_format($totalRent, 2, ',', ' ') }}</span> €
        </div>

        <div class="flex justify-between">
            <span>Frais de service</span>
            <span class="ml-auto" data-update-service>{{ number_format($serviceFee, 2, ',', ' ') }}</span> €
        </div>

        <div class="flex justify-between">
            <span>Taxe de séjour</span>
            <span class="ml-auto" data-update-tourist>{{ number_format($touristTax, 2, ',', ' ') }}</span> €
        </div>
    </div>

    <hr class="border-gray-200 my-6">

    <div class="flex justify-between items-center font-bold text-lg text-slate-900 mb-6">
        <span>Total</span>
        <span class="ml-auto" data-update-total>{{ number_format($total, 2, ',', ' ') }}</span> €
    </div>

    <div class="space-y-4">
        <div class="flex justify-between items-center text-[#EA580C] font-bold">
            <span>À payer maintenant</span>
            <span class="ml-auto" data-update-pay-now>{{ number_format($payNow, 2, ',', ' ') }}</span> €
        </div>

        <div class="flex justify-between items-center text-slate-600">
            <span>Restera à payer sur place</span>
            <span class="ml-auto" data-update-pay-later>{{ number_format($payLater, 2, ',', ' ') }}</span> €
        </div>
    </div>

    <p class="text-xs text-gray-500 mt-6 leading-relaxed">
        Ce paiement nous sert à garantir votre réservation. Il inclut un acompte pour la location, les frais de service et la taxe de séjour. Retrouvez le détail de nos conditions d'annulation ici.
    </p>

</div>


                        </div>
                    </div>

                </div>
            </form>
        </div>
    </div>

    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const maxCapacity = parseInt(document.getElementById('max-capacity').textContent);
            const maxBabies = 6;
            const minAdults = 1;
            const buttons = document.querySelectorAll('.btn-plus, .btn-minus');

            function getTotalGuests() {
                const adults = parseInt(document.getElementById('input-adults').value) || 0;
                const children = parseInt(document.getElementById('input-children').value) || 0;
                return adults + children;
            }

            function updateButtonStates() {
                const totalGuests = getTotalGuests();
                const adults = parseInt(document.getElementById('input-adults').value) || 0;
                const babies = parseInt(document.getElementById('input-babies').value) || 0;
                
                document.querySelectorAll('.btn-plus, .btn-minus').forEach(btn => {
                    const targetName = btn.getAttribute('data-target');
                    const currentValue = parseInt(document.getElementById('input-' + targetName).value) || 0;
                    let shouldDisable = false;

                    if (btn.classList.contains('btn-plus')) {
                        if (targetName === 'babies') {
                            shouldDisable = currentValue >= maxBabies;
                        } else {
                            shouldDisable = totalGuests >= maxCapacity;
                        }
                    } else if (btn.classList.contains('btn-minus')) {
                        if (targetName === 'adults') {
                            shouldDisable = currentValue <= minAdults;
                        } else {
                            shouldDisable = currentValue === 0;
                        }
                    }

                    if (shouldDisable) {
                        btn.disabled = true;
                        btn.classList.add('opacity-50', 'cursor-not-allowed');
                    } else {
                        btn.disabled = false;
                        btn.classList.remove('opacity-50', 'cursor-not-allowed');
                    }
                });
            }

            buttons.forEach(button => {
                button.addEventListener('click', function(e) {
                    e.preventDefault();
                    
                    const targetName = this.getAttribute('data-target');
                    const input = document.getElementById('input-' + targetName);
                    const display = document.getElementById('count-' + targetName);
                    let currentValue = parseInt(input.value) || 0;
                    const totalGuests = getTotalGuests();

                    if (this.classList.contains('btn-plus')) {
                        if (targetName === 'babies') {
                            if (currentValue < maxBabies) {
                                currentValue++;
                            }
                        } else {
                            if (totalGuests < maxCapacity) {
                                currentValue++;
                            }
                        }
                    } else if (this.classList.contains('btn-minus')) {
                        if (targetName === 'adults') {
                            if (currentValue > minAdults) {
                                currentValue--;
                            }
                        } else if (currentValue > 0) {
                            currentValue--;
                        }
                    }

                    input.value = currentValue;
                    display.innerText = currentValue;

                    updateButtonStates();
                    updatePaymentSummary();
                });
            });

            updateButtonStates();
            updatePaymentSummary(); 
        });

        function updatePaymentSummary() {
            const adults = parseInt(document.getElementById('input-adults').value) || 1;
            const children = parseInt(document.getElementById('input-children').value) || 0;
            const babies = parseInt(document.getElementById('input-babies').value) || 0;

            const form = document.getElementById('reservation-form');
            const pricePerNight = parseFloat(form?.dataset.price || 0);
            const nights = parseInt(form?.dataset.nights || 1);

            console.log('Calcul:', { adults, nights, pricePerNight });

            if (pricePerNight > 0 && nights > 0) {
                const totalRent = pricePerNight * nights;
                const serviceFee = totalRent * 0.14;
                const touristTax = 4.00 * nights * adults; 
                const total = totalRent + serviceFee + touristTax;
                const payNow = serviceFee + (totalRent * 0.35);
                const payLater = total - payNow;

                const formatPrice = (value) => {
                    return value.toFixed(2).replace('.', ',').replace(/\B(?=(\d{3})+(?!\d))/g, ' ');
                };

                document.querySelectorAll('[data-update-rent]').forEach(el => {
                    el.textContent = formatPrice(totalRent);
                });
                document.querySelectorAll('[data-update-service]').forEach(el => {
                    el.textContent = formatPrice(serviceFee);
                });
                document.querySelectorAll('[data-update-tourist]').forEach(el => {
                    el.textContent = formatPrice(touristTax);
                });
                document.querySelectorAll('[data-update-total]').forEach(el => {
                    el.textContent = formatPrice(total);
                });
                document.querySelectorAll('[data-update-pay-now]').forEach(el => {
                    el.textContent = formatPrice(payNow);
                });
                document.querySelectorAll('[data-update-pay-later]').forEach(el => {
                    el.textContent = formatPrice(payLater);
                });
            }
        }
    </script>
@endsection