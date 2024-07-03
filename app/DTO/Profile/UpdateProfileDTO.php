<?php

namespace App\DTO\Profile;

use Spatie\DataTransferObject\DataTransferObject;

class UpdateProfileDTO extends DataTransferObject
{
    public ?string $prenom = null;
    public ?string $nom = null;
    public ?string $statut = null;
}