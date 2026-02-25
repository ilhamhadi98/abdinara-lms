<?php

namespace App\Filament\Resources;

use App\Models\User;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Support\Facades\Hash;
use Spatie\Permission\Models\Role;

class UserResource extends Resource
{
    protected static ?string $model = User::class;
    protected static ?string $navigationIcon = 'heroicon-o-users';
    protected static ?string $navigationGroup = 'Manajemen Pengguna';
    protected static ?string $navigationLabel = 'Data Pengguna';
    protected static ?string $pluralLabel = 'Pengguna';
    protected static ?string $pluralModelLabel = 'Pengguna';
    protected static ?string $modelLabel = 'Pengguna';
    protected static ?int $navigationSort = 4;

    public static function canAccess(): bool
    {
        // Hanya super-admin yang bisa manage user
        return auth()->user()?->hasRole('super-admin') ?? false;
    }

    public static function form(Form $form): Form
    {
        return $form->schema([
            Forms\Components\Section::make()->schema([
                Forms\Components\TextInput::make('name')
                    ->label('Nama')
                    ->required()
                    ->maxLength(255),

                Forms\Components\TextInput::make('email')
                    ->label('Email')
                    ->email()
                    ->required()
                    ->maxLength(255)
                    ->unique(ignoreRecord: true),

                Forms\Components\TextInput::make('password')
                    ->label('Password')
                    ->password()
                    ->dehydrateStateUsing(fn ($state) => Hash::make($state))
                    ->dehydrated(fn ($state) => filled($state))
                    ->required(fn (string $operation): bool => $operation === 'create')
                    ->helperText('Kosongkan jika tidak ingin ubah password'),

                Forms\Components\TextInput::make('telegram_chat_id')
                    ->label('ID Chat Telegram')
                    ->numeric()
                    ->maxLength(255)
                    ->helperText('Diperlukan untuk otentikasi OTP login Admin'),

                Forms\Components\Select::make('roles')
                    ->label('Role')
                    ->multiple()
                    ->relationship('roles', 'name')
                    ->options(Role::pluck('name', 'id'))
                    ->preload(),
            ])->columns(2),
        ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('name')
                    ->label('Nama')
                    ->searchable()
                    ->sortable(),
                Tables\Columns\TextColumn::make('email')
                    ->label('Email')
                    ->searchable(),
                Tables\Columns\TextColumn::make('roles.name')
                    ->label('Role')
                    ->badge()
                    ->color(fn ($state) => match($state) {
                        'super-admin' => 'danger',
                        'admin'       => 'warning',
                        'member'      => 'success',
                        default       => 'gray',
                    }),
                Tables\Columns\TextColumn::make('subscription_expires_at')
                    ->label('Berakhir (Premium)')
                    ->dateTime('d M Y')
                    ->sortable()
                    ->badge()
                    ->color(fn ($state) => $state && \Carbon\Carbon::parse($state)->isFuture() ? 'success' : 'danger')
                    ->description(fn (\App\Models\User $record): string => $record->subscription_expires_at && $record->subscription_expires_at->isFuture() 
                        ? round(now()->diffInDays($record->subscription_expires_at)) . ' Hari' 
                        : 'Tidak Aktif'),
                Tables\Columns\TextColumn::make('created_at')
                    ->label('Bergabung')
                    ->dateTime('d M Y')
                    ->sortable(),
            ])
            ->filters([
                Tables\Filters\SelectFilter::make('role')
                    ->label('Role')
                    ->relationship('roles', 'name'),
            ])
            ->defaultSort('created_at', 'desc')
            ->actions([
                Tables\Actions\EditAction::make(),
                Tables\Actions\DeleteAction::make(),
            ])
            ->bulkActions([
                Tables\Actions\BulkActionGroup::make([
                    Tables\Actions\DeleteBulkAction::make(),
                ]),
            ]);
    }

    public static function getPages(): array
    {
        return [
            'index'  => UserResource\Pages\ListUsers::route('/'),
            'create' => UserResource\Pages\CreateUser::route('/create'),
            'edit'   => UserResource\Pages\EditUser::route('/{record}/edit'),
        ];
    }
}
