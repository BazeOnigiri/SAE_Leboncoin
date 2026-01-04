@extends('layouts.app')

@section('content')
<div class="container mx-auto py-8">
    <h1 class="text-2xl font-bold mb-6">Tableau de bord - État des locations</h1>
    
    <div class="w-full">
        <iframe 
            title="Rapport état des locations" 
            width="100%" 
            height="700" 
            src="https://app.powerbi.com/view?r=eyJrIjoiOGRlMWFmYWMtYjdhMy00NWUxLWEzZWYtNjVlMGExNWQ4N2MxIiwidCI6ImUyMWU5NzgzLWQwYTAtNDhmOC04NTBlLTBiMDgxYjQ2ZDc4OCIsImMiOjh9&pageName=2f1f14f0523ed06a4da1"
            frameborder="0" 
            allowFullScreen="true">
        </iframe>
    </div>
</div>
@endsection
