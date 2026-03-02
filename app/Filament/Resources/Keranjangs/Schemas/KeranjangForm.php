<?php

namespace App\Filament\Resources\Keranjangs\Schemas;

use Filament\Forms\Components\TextInput;
use Filament\Schemas\Schema;
use Filament\Forms\Components\Select;


class KeranjangForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema->components([

            Select::make('produk_id')
                ->label('Produk')
                ->relationship('produk', 'nama')
                ->searchable()
                ->required(),

            TextInput::make('jumlah')
                ->numeric()
                ->default(1)
                ->required(),

        ]);
    }
}
