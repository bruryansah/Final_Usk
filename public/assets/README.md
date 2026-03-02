- Membuat fitur keranjang

- php artisan make:model Keranjang -m
- php artisan make:controller KeranjangController     
- php artisan make:filament-resource KeranjangResource
- php artisan make:policy KeranjangPolicy --model=Keranjang

- Model

<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;


class Keranjang extends Model
{
    use HasFactory;
    protected $fillable = ['user_id', 'produk_id', 'jumlah'];

    public function produk()
    {
        return $this->belongsTo(Produk::class);
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}


- Controller
<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\Keranjang;
use Illuminate\Support\Facades\DB;
use App\Models\Produk;
class KeranjangController extends Controller
{
public function store(Request $request)
{
DB::transaction(function () use ($request) {

$produk = Produk::lockForUpdate()->findOrFail($request->produk_id);

// cek stok cukup
if ($produk->stok < $request->jumlah) {
    abort(400, 'Stok tidak mencukupi');
    }

    // simpan ke keranjang
    Keranjang::create([
    'user_id' => Auth::id(),
    'produk_id' => $request->produk_id,
    'jumlah' => $request->jumlah,
    ]);

    // kurangi stok
    $produk->decrement('stok', $request->jumlah);
    });

    return redirect('/admin/keranjangs');
    }

}

- Resource
 - keranjangtable.php :
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

 - keranjang form
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

    - polcies
            public function viewAny(User $user): bool
    {
        return Auth::user()->role === 'Guest';
    }

- web.php
    Route::post('/keranjang/store', [KeranjangController::class, 'store'])
->middleware('auth');

- css & js, blade
