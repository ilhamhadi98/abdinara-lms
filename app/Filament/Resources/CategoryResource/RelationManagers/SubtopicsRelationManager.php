<?php

namespace App\Filament\Resources\CategoryResource\RelationManagers;

use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\RelationManagers\RelationManager;
use Filament\Tables;
use Filament\Tables\Table;

class SubtopicsRelationManager extends RelationManager
{
    protected static string $relationship = 'subtopics';
    protected static ?string $title = 'Subtopik';

    public function form(Form $form): Form
    {
        return $form->schema([
            Forms\Components\TextInput::make('name')
                ->label('Nama Subtopik')
                ->required()
                ->maxLength(100)
                ->columnSpanFull(),
        ]);
    }

    public function table(Table $table): Table
    {
        return $table
            ->recordTitleAttribute('name')
            ->columns([
                Tables\Columns\TextColumn::make('name')
                    ->label('Nama Subtopik')
                    ->searchable(),
                Tables\Columns\TextColumn::make('questions_count')
                    ->label('Soal')
                    ->counts('questions')
                    ->badge()
                    ->color('success'),
            ])
            ->headerActions([
                Tables\Actions\CreateAction::make()->label('Tambah Subtopik'),
            ])
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
}
