<?php

namespace App\Filament\Resources;

use App\Filament\Resources\CompanySubscriptionResource\Pages;
use App\Filament\Resources\CompanySubscriptionResource\RelationManagers;
use App\Models\CompanySubscription;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\DatePicker;
use Filament\Forms\Components\TextInput;
use Filament\Tables\Columns\TextColumn;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;
use Filament\Tables\Filters\Filter;
use Filament\Tables\Filters\SelectFilter;
use App\Models\Company;

class CompanySubscriptionResource extends Resource
{
    protected static ?string $model = CompanySubscription::class;

    protected static ?string $navigationIcon = 'heroicon-o-rectangle-stack';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Select::make('company_id')
                    ->label('Company')
                    ->required()
                    ->options(Company::all()->pluck('name_en', 'id')->toArray())
                    ->searchable(),

                DatePicker::make('subscribe_start')
                    ->label('Subscription Start')
                    ->required(),

                DatePicker::make('subscribe_end')
                    ->label('Subscription End')
                    ->required(),

                TextInput::make('number_employees')
                    ->label('Number of Employees')
                    ->numeric()
                    ->required()
                    ->minValue(1),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('id')->sortable(),
                TextColumn::make('company.name_en')->label('Company (EN)')->searchable(),
                TextColumn::make('subscribe_start')->label('Start Date')->date(),
                TextColumn::make('subscribe_end')->label('End Date')->date(),
                TextColumn::make('number_employees')->label('Number of Employees')->sortable(),
                TextColumn::make('created_at')->label('Created At')->dateTime(),
            ])
            ->filters([
            SelectFilter::make('company_id')
                ->label('Company')
                ->options(Company::all()->pluck('name_en', 'id')->toArray())
                ->searchable(),

            Filter::make('subscribe_start')
                ->form([
                    DatePicker::make('subscribe_start_from')->label('Start From'),
                    DatePicker::make('subscribe_start_until')->label('Start Until'),
                ])
                ->query(function (Builder $query, array $data) {
                    return $query
                        ->when($data['subscribe_start_from'], fn($q) => $q->whereDate('subscribe_start', '>=', $data['subscribe_start_from']))
                        ->when($data['subscribe_start_until'], fn($q) => $q->whereDate('subscribe_start', '<=', $data['subscribe_start_until']));
                }),

            Filter::make('subscribe_end')
                ->form([
                    DatePicker::make('subscribe_end_from')->label('End From'),
                    DatePicker::make('subscribe_end_until')->label('End Until'),
                ])
                ->query(function (Builder $query, array $data) {
                    return $query
                        ->when($data['subscribe_end_from'], fn($q) => $q->whereDate('subscribe_end', '>=', $data['subscribe_end_from']))
                        ->when($data['subscribe_end_until'], fn($q) => $q->whereDate('subscribe_end', '<=', $data['subscribe_end_until']));
                }),

            Filter::make('number_employees')
                ->form([
                    TextInput::make('number_employees_min')->label('Minimum Employees')->numeric(),
                    TextInput::make('number_employees_max')->label('Maximum Employees')->numeric(),
                ])
                ->query(function (Builder $query, array $data) {
                    return $query
                        ->when($data['number_employees_min'], fn($q) => $q->where('number_employees', '>=', $data['number_employees_min']))
                        ->when($data['number_employees_max'], fn($q) => $q->where('number_employees', '<=', $data['number_employees_max']));
                }),
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

    public static function getRelations(): array
    {
        return [
            //
        ];
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListCompanySubscriptions::route('/'),
            'create' => Pages\CreateCompanySubscription::route('/create'),
            'edit' => Pages\EditCompanySubscription::route('/{record}/edit'),
        ];
    }
}
