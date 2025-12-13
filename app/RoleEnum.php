<?php

namespace App;

enum RoleEnum: string
{
    CASE SUPER_ADMIN = 'Super Admin';
    
    CASE SERVICE_PETITE_ANNONCE = 'Service Petite Annonce';
    CASE DIRECTEUR_SERVICE_PETITE_ANNONCE = 'Directeur Service Petite Annonce';
    
    CASE SERVICE_IMOBILIER = 'Service Immobilier';
    CASE DIRECTEUR_SERVICE_IMOBILIER = 'Directeur Service Immobilier';
    
    CASE SERVICE_INSCRIPTION = 'Service Inscription';
    CASE DIRECTEUR_SERVICE_INSCRIPTION = 'Directeur Service Inscription';

    CASE SERVICE_LOCATION = 'Service Location';
    CASE DIRECTEUR_SERVICE_LOCATION = 'Directeur Service Location';
}
