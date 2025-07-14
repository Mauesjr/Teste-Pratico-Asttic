<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;


class UsuarioSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        DB::table('usuarios')->insert([
            [
                'nome' => 'submissor1',
                'email' => 'submissor1@exemplo.com',
                'senha' => Hash::make('senha123'),
                'tipo' => 'submissor',
                'criado_em' => now(),
            ],
            [
                'nome' => 'avaliador1',
                'email' => 'avaliador1@exemplo.com',
                'senha' => Hash::make('senha123'),
                'tipo' => 'avaliador',
                'criado_em' => now(),
            ],
            [
                'nome' => 'decisor1',
                'email' => 'decisor1@examplo.com',
                'senha' => Hash::make('senha123'),
                'tipo' => 'decisor',
                'criado_em' => now(),
            ],
        ]);
    }
}
