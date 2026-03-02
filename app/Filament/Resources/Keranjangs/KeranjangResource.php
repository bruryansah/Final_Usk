<?php

namespace App\Filament\Resources\Keranjangs;

use App\Filament\Resources\Keranjangs\Pages\CreateKeranjang;
use App\Filament\Resources\Keranjangs\Pages\EditKeranjang;
use App\Filament\Resources\Keranjangs\Pages\ListKeranjangs;
use App\Filament\Resources\Keranjangs\Schemas\KeranjangForm;
use App\Filament\Resources\Keranjangs\Tables\KeranjangsTable;
use App\Models\Keranjang;
use BackedEnum;
use Filament\Resources\Resource;
use Filament\Schemas\Schema;
use Filament\Support\Icons\Heroicon;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;

class KeranjangResource extends Resource
{
    protected static ?string $model = Keranjang::class;

    protected static string|BackedEnum|null $navigationIcon = Heroicon::OutlinedRectangleStack;

    protected static ?string $recordTitleAttribute = 'Keranjang';

    public static function form(Schema $schema): Schema
    {
        return KeranjangForm::configure($schema);
    }

    public static function table(Table $table): Table
    {
        return KeranjangsTable::configure($table);
    }

    public static function getRelations(): array
    {
        return [
            //
        ];
    }

    public static function getPages(): array
    {
        return [
            'index' => ListKeranjangs::route('/'),
            'create' => CreateKeranjang::route('/create'),
            'edit' => EditKeranjang::route('/{record}/edit'),
        ];
    }

    public static function getEloquentQuery(): Builder
    {
        $query = parent::getEloquentQuery();

        // Admin lihat semua
        if (auth()->user()->role === 'Admin') {
            return $query;
        }

        // User biasa hanya lihat pembeliannya sendiri
        return $query->where('user_id', auth()->id());
    }
}
