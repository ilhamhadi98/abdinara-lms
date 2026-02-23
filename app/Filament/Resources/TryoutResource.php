<?php

namespace App\Filament\Resources;

use App\Models\Tryout;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Notifications\Notification;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;

class TryoutResource extends Resource
{
    protected static ?string $model = Tryout::class;
    protected static ?string $navigationIcon = 'heroicon-o-clipboard-document-list';
    protected static ?string $navigationGroup = 'Manajemen Bank Soal & Tryout';
    protected static ?string $navigationLabel = 'Tryout';
    protected static ?string $pluralModelLabel = 'Tryout';
    protected static ?string $modelLabel = 'Tryout';
    protected static ?int $navigationSort = 3;

    public static function canAccess(): bool
    {
        return auth()->user()?->hasAnyRole(['admin', 'super-admin']) ?? false;
    }

    public static function form(Form $form): Form
    {
        return $form->schema([
            Forms\Components\Section::make()->schema([
                Forms\Components\TextInput::make('title')
                    ->label('Judul Tryout')
                    ->required()
                    ->maxLength(255)
                    ->columnSpanFull(),

                Forms\Components\TextInput::make('duration_minutes')
                    ->label('Durasi (menit)')
                    ->required()
                    ->numeric()
                    ->minValue(5)
                    ->maxValue(480)
                    ->default(90),

                Forms\Components\TextInput::make('total_questions')
                    ->label('Jumlah Soal')
                    ->required()
                    ->numeric()
                    ->minValue(1)
                    ->default(100)
                    ->helperText('Soal dipilih secara acak dari bank soal'),

                Forms\Components\Toggle::make('is_active')
                    ->label('Aktif / Dipublish')
                    ->default(false),
            ])->columns(2),
        ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('title')
                    ->label('Judul')
                    ->searchable()
                    ->sortable(),
                Tables\Columns\TextColumn::make('duration_minutes')
                    ->label('Durasi')
                    ->suffix(' menit')
                    ->sortable(),
                Tables\Columns\TextColumn::make('total_questions')
                    ->label('Soal')
                    ->badge(),
                Tables\Columns\TextColumn::make('questions_count')
                    ->label('Soal Tersedia')
                    ->counts('questions')
                    ->badge()
                    ->color('success'),
                Tables\Columns\IconColumn::make('is_active')
                    ->label('Status')
                    ->boolean()
                    ->trueIcon('heroicon-o-check-circle')
                    ->falseIcon('heroicon-o-x-circle')
                    ->trueColor('success')
                    ->falseColor('danger'),
                Tables\Columns\TextColumn::make('created_at')
                    ->label('Dibuat')
                    ->dateTime('d M Y')
                    ->sortable(),
            ])
            ->defaultSort('created_at', 'desc')
            ->actions([
                Tables\Actions\Action::make('toggle_publish')
                    ->label(fn (Tryout $record) => $record->is_active ? 'Nonaktifkan' : 'Publish')
                    ->icon(fn (Tryout $record) => $record->is_active ? 'heroicon-o-eye-slash' : 'heroicon-o-eye')
                    ->color(fn (Tryout $record) => $record->is_active ? 'warning' : 'success')
                    ->action(function (Tryout $record) {
                        $record->update(['is_active' => !$record->is_active]);
                        Notification::make()
                            ->title($record->is_active ? 'Tryout dipublish!' : 'Tryout dinonaktifkan')
                            ->success()
                            ->send();
                    }),
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
            'index'  => TryoutResource\Pages\ListTryouts::route('/'),
            'create' => TryoutResource\Pages\CreateTryout::route('/create'),
            'edit'   => TryoutResource\Pages\EditTryout::route('/{record}/edit'),
        ];
    }
}
