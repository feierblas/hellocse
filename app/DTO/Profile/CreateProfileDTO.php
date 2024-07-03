<?php

namespace App\DTO\Profile;

use Spatie\DataTransferObject\DataTransferObject;

class CreateProfileDTO extends DataTransferObject
{
    public string $prenom;
    public string $nom;
    public string $statut;
}