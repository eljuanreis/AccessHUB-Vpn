<?php

namespace App\Database\Seeders;

class Seeder
{
    /**
     * Define o que vai ser executado pelo php bin/seed
     */
    public function load(): array
    {
        return [
            new FirstUserSeeder(),
        ];
    }
}
