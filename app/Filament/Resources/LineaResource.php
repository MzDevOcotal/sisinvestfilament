<?php

namespace App\Filament\Resources;

use App\Filament\Resources\LineaResource\Pages;
use App\Models\Area;
use App\Models\Linea;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;

class LineaResource extends Resource
{
    protected static ?string $model = Linea::class;

    protected static ?string $navigationIcon = 'heroicon-o-paper-airplane';

    protected static ?string $navigationGroup = 'Gestionar';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\TextInput::make('name')
                    ->required(),
                Forms\Components\Select::make('area_id')
                    ->label('Área del Conocimiento (Año)') // Etiqueta descriptiva
                    ->options(function () {
                        // Carga todas las áreas con su relación 'cine' para evitar N+1 queries
                        $areas = Area::with('cine')->get();

                        // Mapea la colección para crear un array de opciones personalizado
                        return $areas->mapWithKeys(function ($area) {
                            return [$area->id => $area->name.' ('.$area->cine->name.')'];
                        })->toArray();
                    })
                    ->required(),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('name')
                    ->searchable(),
                Tables\Columns\TextColumn::make('created_at')
                    ->dateTime()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
                Tables\Columns\TextColumn::make('updated_at')
                    ->dateTime()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
                Tables\Columns\TextColumn::make('area.name')
                    ->sortable(),
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
            'index' => Pages\ListLineas::route('/'),
            'create' => Pages\CreateLinea::route('/create'),
            'edit' => Pages\EditLinea::route('/{record}/edit'),
        ];
    }
}
