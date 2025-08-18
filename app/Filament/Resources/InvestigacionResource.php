<?php

namespace App\Filament\Resources;

use App\Filament\Resources\InvestigacionResource\Pages;
use App\Filament\Resources\InvestigacionResource\RelationManagers;
use App\Models\Area;
use App\Models\Investigacion;
use Filament\Forms;
use Filament\Forms\Components\FileUpload;
use Filament\Forms\Components\Section;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;

class InvestigacionResource extends Resource
{
    protected static ?string $model = Investigacion::class;
    protected static ?string $navigationGroup = 'Gestionar';
    protected static ?string $navigationIcon = 'heroicon-o-rectangle-stack';

    public static function form(Form $form): Form
    {
        return $form

            ->schema([
                Section::make('Documento de Investigación')
                    ->columns(3)
                    ->description('Agregar los Datos de la Investigación')
                    ->schema([
                        Forms\Components\TextInput::make('name')
                            ->label('Título de la Investigación')
                            ->required(),
                        Forms\Components\TextInput::make('avance')
                            ->placeholder('Ejemplo: 50')
                            ->required()
                            ->numeric(),
                        Forms\Components\Select::make('area_id')
                            ->label('Área del Conocimiento (Año)') // Etiqueta descriptiva
                            ->searchable()
                            ->placeholder('Seleccione una opción')
                            ->options(function () {
                                // Carga todas las áreas con su relación 'cine' para evitar N+1 queries
                                $areas = Area::with('cine')->get();

                                // Mapea la colección para crear un array de opciones personalizado
                                return $areas->mapWithKeys(function ($area) {
                                    return [$area->id => $area->name . ' (' . $area->cine->name . ')'];
                                })->toArray();
                            })
                            ->required(),
                        Forms\Components\Select::make('sede_id')
                            ->relationship('sede', 'name')
                            ->placeholder('Seleccione una opción')
                            ->required(),
                        Forms\Components\Select::make('categoria_id')
                            ->relationship('categoria', 'name')
                            ->searchable()
                            ->preload()
                            ->placeholder('Seleccione una opción')
                            ->required(),
                        Forms\Components\Select::make('year_id')
                            ->relationship('year', 'anioacademico')
                            ->placeholder('Seleccione una opción')
                            ->required(),
                        Forms\Components\Select::make('enfoque_id')
                            ->relationship('enfoque', 'name')
                            ->placeholder('Seleccione una opción')
                            ->required(),
                        Forms\Components\Select::make('estado_id')
                            ->relationship('estado', 'name')
                            ->placeholder('Seleccione una opción')
                            ->required(),
                        Forms\Components\Select::make('linea_id')
                            ->relationship('linea', 'name')
                            ->placeholder('Seleccione una opción')
                            ->required(),
                    ]),

                Section::make('Documento de Investigación')
                    ->description('Agregar el PDF de la investigación')
                    ->schema([
                        FileUpload::make('PDF')
                            ->label('Investigación')
                            ->disk('public')
                            ->directory('pdf'),

                    ])
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('name')
                    ->searchable(),
                Tables\Columns\TextColumn::make('avance')
                    ->numeric()
                    ->sortable(),
                Tables\Columns\TextColumn::make('area.name')
                    ->numeric()
                    ->sortable(),
                Tables\Columns\TextColumn::make('sede.name')
                    ->numeric()
                    ->sortable(),
                Tables\Columns\TextColumn::make('categoria.name')
                    ->numeric()
                    ->sortable(),
                Tables\Columns\TextColumn::make('year.anioacademico')
                    ->sortable(),
                Tables\Columns\TextColumn::make('enfoque.name')
                    ->numeric()
                    ->sortable(),
                Tables\Columns\TextColumn::make('estado.name')
                    ->numeric()
                    ->sortable(),
                Tables\Columns\TextColumn::make('linea.name')
                    ->numeric()
                    ->sortable(),
                Tables\Columns\TextColumn::make('PDF')
                    ->label('Documento PDF')
                    ->icon('heroicon-o-document-text')
                    ->url(fn($record) => asset('storage/' . $record->PDF))
                    ->openUrlInNewTab()
                    ->html()
                    ->formatStateUsing(fn($state): string => '&nbsp;'),

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
            'index' => Pages\ListInvestigacions::route('/'),
            'create' => Pages\CreateInvestigacion::route('/create'),
            'edit' => Pages\EditInvestigacion::route('/{record}/edit'),
        ];
    }
}
