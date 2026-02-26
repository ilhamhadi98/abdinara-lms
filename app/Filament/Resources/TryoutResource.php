<?php

namespace App\Filament\Resources;

use App\Models\Category;
use App\Models\Subtopic;
use App\Models\Tryout;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Forms\Get;
use Filament\Notifications\Notification;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Support\Collection;

class TryoutResource extends Resource
{
    protected static ?string $model = Tryout::class;
    protected static ?string $navigationIcon = 'heroicon-o-clipboard-document-list';
    protected static ?string $navigationGroup = 'Manajemen Bank Soal & Tryout';
    protected static ?string $navigationLabel = 'Tryout';
    protected static ?string $pluralModelLabel = 'Tryout';
    protected static ?string $modelLabel = 'Tryout';
    protected static ?int $navigationSort = 3;

    public static function canViewAny(): bool
    {
        return auth()->user()?->hasPermissionTo('view_any_tryout') ?? false;
    }

    public static function canCreate(): bool
    {
        return auth()->user()?->hasPermissionTo('create_tryout') ?? false;
    }

    public static function canEdit(\Illuminate\Database\Eloquent\Model $record): bool
    {
        return auth()->user()?->hasPermissionTo('update_tryout') ?? false;
    }

    public static function canDelete(\Illuminate\Database\Eloquent\Model $record): bool
    {
        return auth()->user()?->hasPermissionTo('delete_tryout') ?? false;
    }

    public static function canDeleteAny(): bool
    {
        return auth()->user()?->hasPermissionTo('delete_any_tryout') ?? false;
    }

    public static function form(Form $form): Form
    {
        return $form->schema([
            Forms\Components\Section::make('Informasi Dasar')->schema([
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
                    ->helperText('Soal akan dipilih secara acak sesuai filter di bawah ini'),

                Forms\Components\Toggle::make('is_active')
                    ->label('Aktif / Dipublish')
                    ->default(false),
            ])->columns(2),

            Forms\Components\Section::make('Filter Sumber Soal')->schema([
                Forms\Components\Select::make('category_id')
                    ->label('Kategori')
                    ->options(Category::orderBy('name')->pluck('name', 'id'))
                    ->placeholder('Mix All (Semua Kategori)')
                    ->live()
                    ->afterStateUpdated(fn (Forms\Set $set) => $set('subtopic_id', null)),

                Forms\Components\Select::make('subtopic_id')
                    ->label('Subtopik')
                    ->options(fn (Get $get): Collection => Subtopic::where('category_id', $get('category_id'))
                        ->orderBy('name')
                        ->pluck('name', 'id'))
                    ->placeholder('Mix All (Semua Subtopik)')
                    ->disabled(fn (Get $get) => empty($get('category_id')))
                    ->live(),

                Forms\Components\Select::make('difficulty')
                    ->label('Tingkat Kesulitan')
                    ->options([
                        1 => 'Mudah',
                        2 => 'Sedang',
                        3 => 'Sulit'
                    ])
                    ->placeholder('Mix All (Semua Tingkat)'),
            ])->columns(3)->description('Biarkan kosong (Mix All) jika ingin mengambil soal secara acak dari semua kategori/tingkat.'),
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
