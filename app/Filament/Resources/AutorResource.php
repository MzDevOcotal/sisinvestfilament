<?php

namespace App\Filament\Resources;

use App\Filament\Resources\AutorResource\Pages;
use App\Models\Autor;
use App\Models\City;
use App\Models\State;
use Filament\Forms;
use Filament\Forms\Components\FileUpload;
use Filament\Forms\Components\Section;
use Filament\Forms\Components\Select;
use Filament\Forms\Form;
use Filament\Forms\Get;
use Filament\Forms\Set;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Columns\ImageColumn;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Filters\SelectFilter;
use Filament\Tables\Table;
use Illuminate\Support\Collection;
use pxlrbt\FilamentExcel\Actions\Tables\ExportBulkAction;

class AutorResource extends Resource
{
    protected static ?string $model = Autor::class;

    protected static ?string $navigationGroup = 'Personas';

    protected static ?string $navigationLabel = 'Autores';

    protected static ?string $navigationIcon = 'heroicon-o-pencil-square';

    public static function getNavigationBadge(): ?string
    {
        return parent::getEloquentQuery()->where('Estado', 'Activo')->count();
        /* return static::getModel()::count(); */
    }

    public static function form(Form $form): Form
    {
        return $form
            ->schema([

                Section::make('Datos Generales del Autor')
                    ->columns(3)
                    ->description('Agregar la foto del autor y sus datos generales')
                    ->schema([
                        FileUpload::make('imagen')
                            ->avatar()
                            ->label('Foto de perfil')
                            ->disk('public')
                            ->directory('imginvestigadores')
                            ->imageEditor()
                            ->circleCropper()
                            ->imageEditorViewportWidth('1920')
                            ->imageEditorViewportHeight('1080'),
                        Forms\Components\TextInput::make('nombres')
                            ->required(),
                        Forms\Components\TextInput::make('apellidos')
                            ->required(),
                        Forms\Components\TextInput::make('cedula')
                            ->required(),
                        Forms\Components\TextInput::make('celular')
                            ->required(),
                        Forms\Components\TextInput::make('direccion')
                            ->required(),
                        Forms\Components\TextInput::make('correo')
                            ->required(),
                    ]),

                Section::make('Aspectos Académicos del Autor')
                    ->columns(3)
                    ->description('Agregar los datos académicos del autor')
                    ->schema([

                        Forms\Components\Select::make('tituloacadautor')
                            ->label('Grado Académico')
                            ->options([
                                'Ingeniería' => 'Ingeniería',
                                'Licenciatura' => 'Licenciatura',
                                'Maestría' => 'Maestría',
                                'Doctorado' => 'Doctorado',
                            ])
                            ->required(),
                        Forms\Components\TextInput::make('orcid')
                            ->label('ORCID')
                            ->required(),
                        Forms\Components\Select::make('sede_id')
                            ->relationship(name: 'sede', titleAttribute: 'name')
                            ->preload()
                            ->searchable(),
                        Forms\Components\Select::make('Estado')
                            ->options([
                                'Activo' => 'Activo',
                                'Suspendido' => 'Suspendido',
                                'Baja' => 'Baja',
                            ])
                            ->searchable(),
                    ]),

                Section::make('Datos Geográficos del Autor')
                    ->columns(3)
                    ->description('Agregar los datos geográficos del autor')
                    ->schema([

                        Forms\Components\Select::make('country_id')
                            ->relationship(name: 'country', titleAttribute: 'name')
                            ->preload()
                            ->live()
                            ->searchable()
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
                ImageColumn::make('imagen')
                    ->disk('public')
                    ->circular()
                    ->size(60),
                Tables\Columns\TextColumn::make('nombres')
                    ->searchable(),
                Tables\Columns\TextColumn::make('apellidos')
                    ->searchable(),

                TextColumn::make('Estado')
                    ->badge()
                    ->color(fn (string $state): string => match ($state) {
                        'Activo' => 'success',
                        'Suspendido' => 'warning',
                        'Baja' => 'danger',
                    }),
                Tables\Columns\TextColumn::make('tituloacadautor')
                    ->label('Grado Académico')
                    ->searchable(),
                Tables\Columns\TextColumn::make('cedula')
                    ->searchable(),
                Tables\Columns\TextColumn::make('celular')
                    ->searchable(),
                Tables\Columns\TextColumn::make('direccion')
                    ->searchable(),
                Tables\Columns\TextColumn::make('correo')
                    ->searchable(),
                Tables\Columns\TextColumn::make('orcid')
                    ->url(fn (?string $state): ?string => $state) // Usa el valor de la columna como la URL
                    ->openUrlInNewTab()
                    ->searchable(),
                Tables\Columns\TextColumn::make('sede.name')
                    ->numeric()
                    ->sortable(),
                Tables\Columns\TextColumn::make('state.name')
                    ->numeric()
                    ->sortable(),
                Tables\Columns\TextColumn::make('country.name')
                    ->numeric()
                    ->sortable(),
                Tables\Columns\TextColumn::make('city.name')
                    ->numeric()
                    ->sortable(),
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
                SelectFilter::make('Estado')
                    ->options([
                        'Activo' => 'Activo',
                        'Suspendido' => 'Suspendido',
                        'Baja' => 'Baja',
                    ]),

            ])
            ->actions([
                Tables\Actions\EditAction::make(),
            ])
            ->bulkActions([
                Tables\Actions\BulkActionGroup::make([
                    Tables\Actions\DeleteBulkAction::make(),
                ]),
                ExportBulkAction::make(),

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
            'index' => Pages\ListAutors::route('/'),
            'create' => Pages\CreateAutor::route('/create'),
            'edit' => Pages\EditAutor::route('/{record}/edit'),
        ];
    }
}
