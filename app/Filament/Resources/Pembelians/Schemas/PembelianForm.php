<?php

namespace App\Filament\Resources\Pembelians\Schemas;

use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Schemas\Schema;

class PembelianForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                TextInput::make('kode_pembelian')
                    ->required()
                    ->disabled(),

                TextInput::make('produk_id')
                    ->required()
                    ->numeric()
                    ->disabled(),

                TextInput::make('banyak')
                    ->required()
                    ->numeric()
                    ->disabled(),

                TextInput::make('bayar')
                    ->required()
                    ->numeric()
                    ->disabled(),

                TextInput::make('user_id')
                    ->required()
                    ->numeric()
                    ->disabled(),

                Select::make('status')
                    ->options([
                        'Verifikasi' => 'Verifikasi',
                        'Proses' => 'Proses',
                        'Kirim' => 'Kirim',
                        'Sampai' => 'Sampai',
                        'Selesai' => 'Selesai',
                    ])
                    ->required(),
            ]);
    }
}
