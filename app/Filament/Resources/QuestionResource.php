<?php

namespace App\Filament\Resources;

use App\Models\Category;
use App\Models\Question;
use App\Models\Subtopic;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Forms\Get;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Support\Collection;

class QuestionResource extends Resource
{
    protected static ?string $model = Question::class;
    protected static ?string $navigationIcon = 'heroicon-o-document-text';

    protected static ?string $navigationGroup = 'Manajemen Bank Soal & Tryout';

    protected static ?string $navigationLabel = 'Bank Soal';

    protected static ?string $pluralLabel = 'Bank Soal';
    protected static ?string $pluralModelLabel = 'Soal';
    protected static ?string $modelLabel = 'Soal';
    protected static ?int $navigationSort = 2;

    public static function canViewAny(): bool
    {
        return auth()->user()?->hasPermissionTo('view_any_question') ?? false;
    }

    public static function canCreate(): bool
    {
        return auth()->user()?->hasPermissionTo('create_question') ?? false;
    }

    public static function canEdit(\Illuminate\Database\Eloquent\Model $record): bool
    {
        return auth()->user()?->hasPermissionTo('update_question') ?? false;
    }

    public static function canDelete(\Illuminate\Database\Eloquent\Model $record): bool
    {
        return auth()->user()?->hasPermissionTo('delete_question') ?? false;
    }

    public static function canDeleteAny(): bool
    {
        return auth()->user()?->hasPermissionTo('delete_any_question') ?? false;
    }

    public static function form(Form $form): Form
    {
        return $form->schema([
            Forms\Components\Section::make('Klasifikasi')->schema([
                Forms\Components\Select::make('category_id')
                    ->label('Kategori')
                    ->options(Category::orderBy('name')->pluck('name', 'id'))
                    ->required()
                    ->live()
                    ->afterStateUpdated(fn (Forms\Set $set) => $set('subtopic_id', null))
                    ->dehydrated(false), // tidak disimpan ke DB

                Forms\Components\Select::make('subtopic_id')
                    ->label('Subtopik')
                    ->options(fn (Get $get): Collection => Subtopic::where('category_id', $get('category_id'))
                        ->orderBy('name')
                        ->pluck('name', 'id'))
                    ->required()
                    ->live(),

                Forms\Components\Select::make('difficulty')
                    ->label('Tingkat Kesulitan')
                    ->options([1 => 'Mudah', 2 => 'Sedang', 3 => 'Sulit'])
                    ->default(1)
                    ->required(),
            ])->columns(3),

            Forms\Components\Section::make('Soal')->schema([
                Forms\Components\Textarea::make('question_text')
                    ->label('Teks Soal')
                    ->required()
                    ->rows(4)
                    ->columnSpanFull(),

                Forms\Components\FileUpload::make('image')
                    ->label('Gambar Soal (opsional)')
                    ->image()
                    ->directory('questions')
                    ->maxSize(2048)
                    ->imageResizeMode('cover')
                    ->imageCropAspectRatio(null)
                    ->imageResizeTargetWidth('1200')
                    ->columnSpanFull(),

                Forms\Components\TextInput::make('option_a')->label('Pilihan A')->required(),
                Forms\Components\TextInput::make('option_b')->label('Pilihan B')->required(),
                Forms\Components\TextInput::make('option_c')->label('Pilihan C')->required(),
                Forms\Components\TextInput::make('option_d')->label('Pilihan D')->required(),
                Forms\Components\TextInput::make('option_e')->label('Pilihan E')->required(),
            ])->columns(2),

            Forms\Components\Section::make('Kunci Jawaban')->schema([
                Forms\Components\Select::make('correct_answer')
                    ->label('Jawaban Benar')
                    ->options(['A' => 'A', 'B' => 'B', 'C' => 'C', 'D' => 'D', 'E' => 'E'])
                    ->required(),
            ]),

            Forms\Components\Section::make('Pembahasan')->schema([
                Forms\Components\Textarea::make('explanation')
                    ->label('Pembahasan Jawaban (opsional)')
                    ->rows(5)
                    ->helperText('Penjelasan mengapa jawaban benar. Ditampilkan saat review hasil tryout.')
                    ->columnSpanFull(),
            ]),
        ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('subtopic.category.name')
                    ->label('Kategori')
                    ->badge()
                    ->sortable(),
                Tables\Columns\TextColumn::make('subtopic.name')
                    ->label('Subtopik')
                    ->sortable(),
                Tables\Columns\TextColumn::make('question_text')
                    ->label('Soal')
                    ->limit(60)
                    ->searchable(),
                Tables\Columns\ImageColumn::make('image')
                    ->label('Gambar')
                    ->circular(false)
                    ->defaultImageUrl(null)
                    ->toggleable(isToggledHiddenByDefault: true),
                Tables\Columns\IconColumn::make('explanation')
                    ->label('Pembahasan')
                    ->boolean()
                    ->getStateUsing(fn ($record) => filled($record->explanation))
                    ->trueIcon('heroicon-o-check-circle')
                    ->falseIcon('heroicon-o-x-circle')
                    ->trueColor('success')
                    ->falseColor('gray'),
                Tables\Columns\BadgeColumn::make('difficulty')
                    ->label('Tingkat')
                    ->formatStateUsing(fn ($state) => match($state) {
                        1 => 'Mudah', 2 => 'Sedang', 3 => 'Sulit', default => '-'
                    })
                    ->colors([
                        'success' => 1,
                        'warning' => 2,
                        'danger'  => 3,
                    ]),
                Tables\Columns\TextColumn::make('created_at')
                    ->label('Dibuat')
                    ->dateTime('d M Y')
                    ->sortable(),
            ])
            ->filters([
                Tables\Filters\SelectFilter::make('category')
                    ->label('Kategori')
                    ->relationship('subtopic.category', 'name'),
                Tables\Filters\SelectFilter::make('difficulty')
                    ->label('Tingkat')
                    ->options([1 => 'Mudah', 2 => 'Sedang', 3 => 'Sulit']),
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
            'index'  => QuestionResource\Pages\ListQuestions::route('/'),
            'create' => QuestionResource\Pages\CreateQuestion::route('/create'),
            'edit'   => QuestionResource\Pages\EditQuestion::route('/{record}/edit'),
        ];
    }
}
