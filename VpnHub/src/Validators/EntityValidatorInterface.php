<?php

namespace App\Validators;

interface EntityValidatorInterface 
{
    public function create($object) : bool;
    public function messages() : array;
}