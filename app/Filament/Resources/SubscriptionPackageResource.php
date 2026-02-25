<?php

namespace App\Filament\Resources;

use App\Filament\Resources\SubscriptionPackageResource\Pages;
use App\Models\SubscriptionPackage;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;

class SubscriptionPackageResource extends Resource
{
    protected static ?string $model = SubscriptionPackage::class;

    protected static ?string $navigationIcon = 'heroicon-o-star';

    protected static ?string $navigationGroup = 'Langganan';

    protected static ?string $navigationLabel = 'Paket Langganan';

    protected static ?string $pluralModelLabel = 'Paket Langganan';

    protected static ?string $modelLabel = 'Paket Langganan';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\TextInput::make('name')
                    ->label('Nama Paket')
                    ->required()
                    ->maxLength(255),
                Forms\Components\TextInput::make('price')
                    ->label('Harga')
                    ->required()
                    ->numeric()
                    ->prefix('Rp'),
                Forms\Components\TextInput::make('duration_days')
                    ->label('Durasi (hari)')
                    ->required()
                    ->numeric()
                    ->helperText('Berapa hari masa aktif untuk paket ini'),
                Forms\Components\Toggle::make('is_active')
                    ->label('Paket Aktif?')
                    ->default(true)
                    ->required(),
                Forms\Components\Textarea::make('description')
                    ->label('Deskripsi Lengkap')
                    ->columnSpanFull(),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('name')
                    ->label('Nama Paket')
                    ->searchable(),
                Tables\Columns\TextColumn::make('price')
                    ->label('Harga')
                    ->money('idr')
                    ->sortable(),
                Tables\Columns\TextColumn::make('duration_days')
                    ->label('Durasi')
                    ->numeric()
                    ->suffix(' Hari')
                    ->sortable(),
                Tables\Columns\IconColumn::make('is_active')
                    ->label('Aktif')
                    ->boolean(),
                Tables\Columns\TextColumn::make('created_at')
                    ->dateTime()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
                Tables\Columns\TextColumn::make('updated_at')
                    ->dateTime()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
            ])
            ->filters([
                //
            ])
            ->actions([
                Tables\Actions\EditAction::make(),
            ])
            ->bulkActions([
                Tables\Actions\BulkActionGroup::make([
                    Tables\Actions\DeleteBulkAction::make(),
                ]),
            ]);
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
            'index' => Pages\ListSubscriptionPackages::route('/'),
            'create' => Pages\CreateSubscriptionPackage::route('/create'),
            'edit' => Pages\EditSubscriptionPackage::route('/{record}/edit'),
        ];
    }
}
