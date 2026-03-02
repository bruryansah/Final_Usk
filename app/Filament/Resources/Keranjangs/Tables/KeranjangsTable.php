<?php

namespace App\Filament\Resources\Keranjangs\Tables;

use Filament\Tables\Table;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Columns\ImageColumn;
use Filament\Actions\EditAction;
use Filament\Actions\DeleteAction;
use Filament\Actions\Action;
use Filament\Actions\BulkActionGroup;
use Filament\Actions\DeleteBulkAction;
use Filament\Actions\BulkAction;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Support\Facades\DB;
use Filament\Notifications\Notification;
use Throwable;
use App\Models\Pembelian;

class KeranjangsTable
{
    public static function configure(Table $table): Table
    {
        return $table
            ->columns([

                TextColumn::make('produk.nama')
                    ->label('Produk')
                    ->searchable(),

                TextColumn::make('jumlah')
                    ->label('Jumlah')
                    ->sortable(),

                TextColumn::make('user.name')
                    ->label('User')
                    ->searchable()
                    ->sortable(),

                TextColumn::make('produk.harga')
                    ->label('Harga')
                    ->money('IDR'),

                ImageColumn::make('produk.image')
                    ->circular(),

                TextColumn::make('created_at')
                    ->dateTime()
                    ->sortable(),
            ])

            ->recordActions([
                EditAction::make(),
                DeleteAction::make(),

                /*
                =========================
                CHECKOUT SATU ITEM
                =========================
                */
                Action::make('checkout')
                    ->label('Checkout')
                    ->icon('heroicon-o-credit-card')
                    ->color('success')
                    ->requiresConfirmation()
                    ->action(function ($record) {

                        DB::beginTransaction();

                        try {
                            $produk = DB::table('produks')
                                ->where('id', $record->produk_id)
                                ->lockForUpdate()
                                ->first();

                            if (!$produk) {
                                throw new \Exception('Produk tidak ditemukan');
                            }

                            if ($produk->stok < $record->jumlah) {
                                DB::rollBack();

                                Notification::make()
                                    ->title('Stok tidak mencukupi')
                                    ->body('Sisa stok: ' . $produk->stok)
                                    ->danger()
                                    ->send();
                                return;
                            }

                            Pembelian::create([
                                'kode_pembelian' => 'P-' . time(),
                                'produk_id' => $record->produk_id,
                                'banyak' => $record->jumlah,
                                'bayar' => $record->jumlah * $produk->harga,
                                'user_id' => auth()->id(),
                                'status' => 'Verifikasi',
                            ]);

                            DB::table('produks')
                                ->where('id', $record->produk_id)
                                ->update([
                                    'stok' => $produk->stok - $record->jumlah
                                ]);

                            $record->delete();

                            DB::commit();

                            Notification::make()
                                ->title('Checkout berhasil')
                                ->success()
                                ->send();

                        } catch (Throwable $e) {
                            DB::rollBack();

                            Notification::make()
                                ->title('Checkout gagal')
                                ->body($e->getMessage())
                                ->danger()
                                ->send();
                        }
                    }),
            ])

            ->toolbarActions([
                BulkActionGroup::make([
                    DeleteBulkAction::make(),

                    /*
                    =========================
                    CHECKOUT SEMUA ITEM
                    =========================
                    */
                    BulkAction::make('checkout_semua')
                        ->label('Checkout Semua')
                        ->icon('heroicon-o-shopping-cart')
                        ->color('success')
                        ->requiresConfirmation()
                        ->action(function (Collection $records) {

                            DB::beginTransaction();

                            try {

                                foreach ($records as $record) {

                                    $produk = DB::table('produks')
                                        ->where('id', $record->produk_id)
                                        ->lockForUpdate()
                                        ->first();

                                    if (!$produk || $produk->stok < $record->jumlah) {
                                        throw new \Exception(
                                            'Stok tidak cukup untuk: ' . $record->produk->nama
                                        );
                                    }

                                    Pembelian::create([
                                        'kode_pembelian' => 'P-' . time() . rand(10, 999),
                                        'produk_id' => $record->produk_id,
                                        'banyak' => $record->jumlah,
                                        'bayar' => $record->jumlah * $produk->harga,
                                        'user_id' => auth()->id(),
                                        'status' => 'Verifikasi',
                                    ]);

                                    DB::table('produks')
                                        ->where('id', $record->produk_id)
                                        ->update([
                                            'stok' => $produk->stok - $record->jumlah
                                        ]);

                                    $record->delete();
                                }

                                DB::commit();

                                Notification::make()
                                    ->title('Semua item berhasil di-checkout')
                                    ->success()
                                    ->send();

                            } catch (Throwable $e) {
                                DB::rollBack();

                                Notification::make()
                                    ->title('Checkout gagal')
                                    ->body($e->getMessage())
                                    ->danger()
                                    ->send();
                            }
                        }),
                ]),
            ]);
    }
}
