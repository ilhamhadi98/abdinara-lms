<?php

namespace App\Filament\Resources;

use App\Filament\Resources\UserTargetResource\Pages;
use App\Filament\Resources\UserTargetResource\RelationManagers;
use App\Models\UserTarget;
use App\Models\User;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;

class UserTargetResource extends Resource
{
    protected static ?string $model = UserTarget::class;

    protected static ?string $navigationIcon = 'heroicon-o-viewfinder-circle';

    protected static ?string $navigationGroup = 'Manajemen Pengguna';

    protected static ?string $navigationLabel = 'Target Pengguna';

    public static function canViewAny(): bool
    {
        return auth()->user()?->hasPermissionTo('view_any_usertarget') ?? false;
    }

    public static function canCreate(): bool
    {
        return auth()->user()?->hasPermissionTo('create_usertarget') ?? false;
    }

    public static function canEdit(\Illuminate\Database\Eloquent\Model $record): bool
    {
        return auth()->user()?->hasPermissionTo('update_usertarget') ?? false;
    }

    public static function canDelete(\Illuminate\Database\Eloquent\Model $record): bool
    {
        return auth()->user()?->hasPermissionTo('delete_usertarget') ?? false;
    }

    public static function canDeleteAny(): bool
    {
        return auth()->user()?->hasPermissionTo('delete_any_usertarget') ?? false;
    }

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\Select::make('user_id')
                    ->relationship('user', 'name')
                    ->searchable()
                    ->preload()
                    ->required(),
                Forms\Components\Select::make('target_type')
                    ->options([
                        'tryout' => 'Selesaikan Tryout',
                        'module' => 'Selesaikan Modul',
                    ])
                    ->required(),
                Forms\Components\TextInput::make('target_value')
                    ->required()
                    ->numeric()
                    ->default(5),
                Forms\Components\TextInput::make('current_value')
                    ->required()
                    ->numeric()
                    ->default(0),
                Forms\Components\DatePicker::make('deadline_date'),
                Forms\Components\Toggle::make('is_completed')
                    ->required(),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('user_id')
                    ->numeric()
                    ->sortable(),
                Tables\Columns\TextColumn::make('target_type')
                    ->searchable(),
                Tables\Columns\TextColumn::make('target_value')
                    ->numeric()
                    ->sortable(),
                Tables\Columns\TextColumn::make('current_value')
                    ->numeric()
                    ->sortable(),
                Tables\Columns\TextColumn::make('deadline_date')
                    ->date()
                    ->sortable(),
                Tables\Columns\IconColumn::make('is_completed')
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
            'index' => Pages\ListUserTargets::route('/'),
            'create' => Pages\CreateUserTarget::route('/create'),
            'edit' => Pages\EditUserTarget::route('/{record}/edit'),
        ];
    }
}
