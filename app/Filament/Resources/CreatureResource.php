<?php

namespace App\Filament\Resources;

use App\Filament\Resources\CreatureResource\Pages;
use App\Filament\Resources\CreatureResource\RelationManagers;
use App\Models\Creature;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\Toggle;
use Filament\Tables\Filters\Filter;
use Filament\Forms\Components\Repeater;

class CreatureResource extends Resource
{
    protected static ?string $model = Creature::class;

    protected static ?string $navigationIcon = 'heroicon-o-rectangle-stack';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                TextInput::make('name')
                    ->required()
                    ->maxLength(255),

                Select::make('size_id')
                    ->required()
                    ->relationship('size', 'name'),

                TextInput::make('level')
                    ->required()
                    ->numeric(),

                TextInput::make('hp')
                    ->required()
                    ->numeric(),

                TextInput::make('ac')
                    ->required()
                    ->numeric(),

                TextInput::make('fortitude')
                    ->required()
                    ->numeric(),

                TextInput::make('reflex')
                    ->required()
                    ->numeric(),

                TextInput::make('will')
                    ->required()
                    ->numeric(),

                TextInput::make('perception')
                    ->required()
                    ->numeric(),

                Textarea::make('senses')
                    ->required()
                    ->columnSpanFull(),

                TextInput::make('speed')
                    ->required()
                    ->maxLength(255),

                Select::make('rarity_id')
                    ->required()
                    ->relationship('rarity', 'name'),

                Toggle::make('custom')
                    ->required(),

                Select::make('user_id')
                    ->relationship('user', 'name'),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('name')->sortable(),
                Tables\Columns\TextColumn::make('hp')->sortable(),
                Tables\Columns\TextColumn::make('level')->sortable(),
                Tables\Columns\TextColumn::make('ac')->sortable(),
                Tables\Columns\IconColumn::make('custom')->sortable()
                    ->label('Custom')
                    ->boolean()
                    ->trueIcon('heroicon-o-check')
                    ->falseIcon('heroicon-o-x-mark')
                    ->trueColor('success')
                    ->falseColor('gray')
                    ->sortable(),

            ])
            ->filters([
                Filter::make('custom')
                    ->query(fn(Builder $query): Builder => $query->where('custom', true))
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
            // THIS GIVES AN ERROR IN IDE EVEN THOUGH IT WORK
            'index' => Pages\ListCreatures::route('/'),
            'create' => Pages\CreateCreature::route('/create'),
            'edit' => Pages\EditCreature::route('/{record}/edit'),
        ];
    }
}
