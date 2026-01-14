<?php

namespace App\Filament\Resources\Customers\Tables;

use Filament\Actions\BulkActionGroup;
use Filament\Actions\DeleteBulkAction;
use Filament\Actions\EditAction;
use Filament\Actions\ForceDeleteBulkAction;
use Filament\Actions\RestoreBulkAction;
use Filament\Tables\Columns\IconColumn;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Filters\TrashedFilter;
use Filament\Tables\Table;

class CustomersTable
{
    public static function configure(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('name')->label('Nome')->searchable()->sortable(),
                TextColumn::make('whatsapp')->label('WhatsApp')->searchable(),
                IconColumn::make('user_id', fn($record) => !null($record->user_id))->label('Aplicativo')->boolean()->hidden(fn() => auth()->user()->role !== 'ADMIN'),
                // TODO: TextColumn::make('company_vehicles_count')->label('Veículos')->counts('companyVehicles')->sortable(),
                // TODO: TextColumn::make('bookings_count')->label('Serviços')->counts('bookings')->sortable(),
            ])
            ->defaultSort('name', 'asc')
            ->filters([
                // TrashedFilter::make(),
            ])
            ->recordActions([
                EditAction::make(),
            ])
            ->toolbarActions([
                // BulkActionGroup::make([
                //     DeleteBulkAction::make(),
                //     ForceDeleteBulkAction::make(),
                //     RestoreBulkAction::make(),
                // ]),
            ]);
    }
}
