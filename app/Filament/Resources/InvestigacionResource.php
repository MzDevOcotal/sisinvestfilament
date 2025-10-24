<?php

namespace App\Filament\Resources;

use App\Filament\Resources\InvestigacionResource\Pages;
use App\Models\Area;
use App\Models\Autor;
use App\Models\Investigacion;
use Filament\Forms;
use Filament\Forms\Components\FileUpload;
use Filament\Forms\Components\Section;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Filters\SelectFilter;
use Filament\Tables\Table;

class InvestigacionResource extends Resource
{
    protected static ?string $model = Investigacion::class;

    protected static ?string $navigationGroup = 'Gestionar';

    protected static ?string $navigationIcon = 'heroicon-o-book-open';

    protected static ?string $navigationLabel = 'Investigaciones';

    public static function getNavigationBadge(): ?string
    {
        return static::getModel()::count();
    }

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
                            ->placeholder('Escriba el título del trabajo de Investigación')
                            ->required(),
                        Forms\Components\Select::make('autores_autores') // Campo para autores
                            ->label('Autores')
                            ->multiple()
                            ->options(
                                Autor::all()->mapWithKeys(function ($autor) {
                                    return [$autor->id => "{$autor->nombres} {$autor->apellidos}"];
                                })
                            )
                            ->preload()
                            ->multiple()
                            ->searchable(),
                        Forms\Components\Select::make('autores_asesores') // Campo para asesores
                            ->label('Asesores')
                            ->options(
                                Autor::all()->mapWithKeys(function ($autor) {
                                    return [$autor->id => "{$autor->nombres} {$autor->apellidos}"];
                                })
                            )
                            ->preload()

                            ->multiple()
                            ->searchable(),
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
                        Forms\Components\TextInput::make('link')
                            ->placeholder('Aquí va el link del trabajo (opcional)'),
                    ]),

                Section::make('Documento de Investigación')
                    ->description('Agregar el PDF de la investigación')
                    ->schema([
                        FileUpload::make('PDF')
                            ->label('Investigación')
                            ->disk('public')
                            ->directory('pdf'),

                    ]),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('name')
                    ->label('Título de la Investigación')
                    ->wrap()
                    ->limit(50)
                    ->tooltip(function (TextColumn $column): ?string {
                        $state = $column->getState();

                        if (strlen($state) <= $column->getCharacterLimit()) {
                            return null;
                        }

                        // Only render the tooltip if the column contents exceeds the length limit.
                        return $state;
                    })
                    ->searchable(),
                Tables\Columns\TextColumn::make('autores')
                    ->label('Autores')
                    ->formatStateUsing(fn($state): string => $state->nombres . ' ' . $state->apellidos)
                    ->badge()
                    ->color('success')
                    ->searchable(),
                Tables\Columns\TextColumn::make('asesores')
                    ->label('Asesores')
                    ->formatStateUsing(fn($state): string => $state->nombres . ' ' . $state->apellidos)
                    ->badge()
                    ->color('info')
                    ->searchable(),

                Tables\Columns\TextColumn::make('avance')
                    ->numeric()
                    ->sortable(),
                Tables\Columns\TextColumn::make('area.name')
                    ->wrap()
                    ->limit(50)
                    ->tooltip(function (TextColumn $column): ?string {
                        $state = $column->getState();

                        if (strlen($state) <= $column->getCharacterLimit()) {
                            return null;
                        }

                        // Only render the tooltip if the column contents exceeds the length limit.
                        return $state;
                    })
                    ->searchable()
                    ->sortable(),
                Tables\Columns\TextColumn::make('sede.name')
                    ->sortable(),
                Tables\Columns\TextColumn::make('categoria.name')
                    ->wrap(100)
                    ->sortable(),
                Tables\Columns\TextColumn::make('year.anioacademico')
                    ->sortable(),
                Tables\Columns\TextColumn::make('enfoque.name')
                    ->sortable(),
                Tables\Columns\TextColumn::make('estado.name')
                    ->sortable(),
                Tables\Columns\TextColumn::make('linea.name')
                    ->wrap()
                    ->limit(50)
                    ->tooltip(function (TextColumn $column): ?string {
                        $state = $column->getState();

                        if (strlen($state) <= $column->getCharacterLimit()) {
                            return null;
                        }

                        // Only render the tooltip if the column contents exceeds the length limit.
                        return $state;
                    })
                    ->sortable(),
                Tables\Columns\TextColumn::make('PDF')
                    ->label('PDF')
                    ->icon('heroicon-o-document-text')
                    ->url(fn($record) => asset('storage/' . $record->PDF))
                    ->openUrlInNewTab()
                    ->html()
                    ->formatStateUsing(fn($state): string => '&nbsp;'),
                Tables\Columns\TextColumn::make('link')
                    ->label('Link del trabajo')
                    ->url(fn(?string $state): ?string => $state) // Usa el valor de la columna como la URL
                    ->openUrlInNewTab()
                    ->toggleable(isToggledHiddenByDefault: true),

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
                SelectFilter::make('estado')
                    ->relationship('estado', 'name')
                    ->options([
                        'En Ejecución' => 'En Ejecución',
                        'Finalizado' => 'Finalizado',
                        'Publicado' => 'Publicado',
                        'No Publicado' => 'No Publicado',
                        'En Revisión' => 'En Revisión',
                        'En Ajuste por el Autor' => 'En Ajuste por el Autor',
                    ]),

                SelectFilter::make('year')
                    ->relationship('year', 'anioacademico')
                    ->options([
                        '2021' => '2021',
                        '2022' => '2022',
                        '2023' => '2023',
                        '2024' => '2024',
                        '2025' => '2025',
                    ]),

            ])
            ->actions([
                Tables\Actions\EditAction::make(),
            ])
            ->modifyQueryUsing(function ($query) {
                return $query
                    ->with('autores')
                    ->orderBy('created_at', 'desc');
            })
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
