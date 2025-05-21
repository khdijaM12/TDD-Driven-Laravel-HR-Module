<?php

namespace App\Filament\Resources;

use App\Filament\Resources\CompanyHolidayResource\Pages;
use App\Filament\Resources\CompanyHolidayResource\RelationManagers;
use App\Models\CompanyHoliday;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\DatePicker;
use Filament\Forms\Components\TextInput;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Columns\DateColumn;
use Filament\Tables\Filters\SelectFilter;
use Filament\Tables\Filters\Filter;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;
use App\Models\Company;
use App\Models\CompanyBranch;

class CompanyHolidayResource extends Resource
{
    protected static ?string $model = CompanyHoliday::class;

    protected static ?string $navigationIcon = 'heroicon-o-sun';

    protected static ?string $navigationLabel = 'Company Holidays';

    protected static ?string $pluralModelLabel = 'Company Holidays';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Select::make('company_id')
                    ->label('Company')
                    ->options(Company::all()->pluck('name_en', 'id'))
                    ->searchable()
                     ->reactive()
                    ->required(),

                Select::make('branch_id')
                    ->label('Branch')
                    ->options(function (callable $get) {
                            return \App\Models\CompanyBranch::getOptionsByCompanyId($get('company_id'));
                        })
                                    ->searchable(),

                TextInput::make('occasion')
                    ->label('Occasion')
                    ->required()
                    ->maxLength(255),

                DatePicker::make('date_from')
                    ->label('Start Date')
                    ->required(),

                DatePicker::make('date_to')
                    ->label('End Date')
                    ->required()
                    ->afterOrEqual('date_from'),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
            TextColumn::make('id')->sortable(),
            TextColumn::make('company.name_en')->label('Company')->searchable(),
            TextColumn::make('branch.name_en')->label('Branch')->searchable(),
            TextColumn::make('occasion')->searchable(),
            TextColumn::make('date_from')->label('From')->date(),
            TextColumn::make('date_to')->label('To')->date(),
            TextColumn::make('created_at')->label('Created At')->dateTime(),
            ])

            ->filters([
            SelectFilter::make('company_id')
                ->label('Company')
                ->options(Company::all()->pluck('name_en', 'id')->toArray())
                ->searchable(),

            SelectFilter::make('branch_id')
                ->label('Branch')
                ->options(function () {
                    return CompanyBranch::all()->pluck('name_en', 'id')->toArray();
                })
                ->searchable(),

            Filter::make('occasion')
                ->form([
                    TextInput::make('occasion')->label('Occasion contains'),
                ])
                ->query(function (Builder $query, array $data) {
                    return $query->when(
                        $data['occasion'] ?? null,
                        fn($q, $occasion) => $q->where('occasion', 'like', '%' . $occasion . '%')
                    );
                }),

            Filter::make('date_from')
                ->form([
                    DatePicker::make('date_from')->label('Start Date From'),
                    DatePicker::make('date_to')->label('Start Date To'),
                ])
                ->query(function (Builder $query, array $data) {
                    return $query
                        ->when($data['date_from'], fn($q) => $q->whereDate('date_from', '>=', $data['date_from']))
                        ->when($data['date_to'], fn($q) => $q->whereDate('date_from', '<=', $data['date_to']));
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
            'index' => Pages\ListCompanyHolidays::route('/'),
            'create' => Pages\CreateCompanyHoliday::route('/create'),
            'edit' => Pages\EditCompanyHoliday::route('/{record}/edit'),
        ];
    }
}
