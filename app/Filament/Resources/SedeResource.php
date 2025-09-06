<?php

namespace App\Filament\Resources;

use App\Filament\Resources\SedeResource\Pages;
use App\Models\City;
use App\Models\Sede;
use App\Models\State;
use Filament\Forms;
use Filament\Forms\Components\Section;
use Filament\Forms\Components\Select;
use Filament\Forms\Form;
use Filament\Forms\Get;
use Filament\Forms\Set;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Support\Collection;

class SedeResource extends Resource
{
    protected static ?string $model = Sede::class;

    protected static ?string $navigationGroup = 'Gestionar';

    protected static ?string $navigationIcon = 'heroicon-o-building-library';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([

                Section::make('Datos Generales de la Sede')
                    ->columns(2)
                    ->description('Registrar los datos de contacto y ubicación de la sede')
                    ->schema([
                        Forms\Components\TextInput::make('name')
                            ->label('Nombre de la Sede')
                            ->required(),
                        Forms\Components\TextInput::make('nombre_director')
                            ->label('Nombre del director de la sede')
                            ->required(),
                        Forms\Components\TextInput::make('celular_contacto')
                            ->label('Número de contacto del director')
                            ->required(),
                        Forms\Components\TextInput::make('email_institucional')
                            ->label('Email institucional del director de la sede')
                            ->required(),

                    ]),

                Section::make('Datos de Ubicación Geográfica')
                    ->columns(3)
                    ->description('Prevent abuse by limiting the number of requests per period')
                    ->schema([
                        Forms\Components\Select::make('country_id')
                            ->relationship(name: 'country', titleAttribute: 'name')
                            ->searchable()
                            ->preload()
                            ->live()
                            // Esta función permite hacer null los otros 2 select dependientes de él al momento de deseleccionar el país.
                            ->afterStateUpdated(function (Set $set) {
                                $set('state_id', null);
                                $set('city_id', null);
                            })
                            ->required(),

                        Forms\Components\Select::make('state_id')
                            ->label('State')
                            ->options(fn (Get $get): Collection => State::query()
                                ->where('country_id', $get('country_id'))
                                ->pluck('name', 'id'))
                            ->searchable()
                            ->preload()
                            ->live()
                            // Esta función permite hacer null el select de ciudad al momento de deseleccionar el estado.
                            ->afterStateUpdated(function (Set $set) {
                                $set('city_id', null);
                            })
                            ->required(),

                        Forms\Components\Select::make('city_id')
                            ->label('City')
                            ->options(fn (Get $get): Collection => City::query()
                                ->where('state_id', $get('state_id'))
                                ->pluck('name', 'id'))
                            ->searchable()
                            ->preload()
                            ->required(),

                    ]),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('name')
                    ->label('Nombre de la Sede')
                    ->searchable(),
                Tables\Columns\TextColumn::make('nombre_director')
                    ->label('Director')
                    ->searchable(),
                Tables\Columns\TextColumn::make('celular_contacto')
                    ->label('Número de contacto')
                    ->searchable(),
                Tables\Columns\TextColumn::make('email_institucional')
                    ->label('Email institucional')
                    ->searchable(),
                Tables\Columns\TextColumn::make('country.name')
                    ->label('País')
                    ->searchable(),
                Tables\Columns\TextColumn::make('state.name')
                    ->label('Departamento')
                    ->searchable(),
                Tables\Columns\TextColumn::make('city.name')
                    ->label('Ciudad')
                    ->searchable(),
                Tables\Columns\TextColumn::make('created_at')
                    ->dateTime()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
                Tables\Columns\TextColumn::make('updated_at')
                    ->dateTime()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
            ])
            ->filters([])
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
            'index' => Pages\ListSedes::route('/'),
            'create' => Pages\CreateSede::route('/create'),
            'edit' => Pages\EditSede::route('/{record}/edit'),
        ];
    }
}
