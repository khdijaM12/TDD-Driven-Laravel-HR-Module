<?php

namespace App\Filament\Resources;

use App\Filament\Resources\CompanyScheduleResource\Pages;
use App\Filament\Resources\CompanyScheduleResource\RelationManagers;
use App\Models\CompanySchedule;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\TimePicker;
use Filament\Forms\Components\CheckboxList;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Filters\Filter;
use Filament\Tables\Filters\SelectFilter;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;
use App\Models\Company;

class CompanyScheduleResource extends Resource
{
    protected static ?string $model = CompanySchedule::class;

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

                TextInput::make('slug')
                    ->label('Slug')
                    ->required()
                    ->unique(ignoreRecord: true)
                    ->maxLength(255),

                CheckboxList::make('weekend_days')
                    ->label('Weekend Days')
                    ->options([
                        'Saturday' => 'Saturday',
                        'Sunday' => 'Sunday',
                        'Monday' => 'Monday',
                        'Tuesday' => 'Tuesday',
                        'Wednesday' => 'Wednesday',
                        'Thursday' => 'Thursday',
                        'Friday' => 'Friday',
                    ])
                    ->required(),

                TimePicker::make('check_in_time')
                    ->label('Check-in Time')
                    ->required(),

                TimePicker::make('check_out_time')
                    ->label('Check-out Time')
                    ->required(),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('id')->sortable(),
                TextColumn::make('company.name_en')->label('Company (EN)')->searchable(),
                TextColumn::make('slug')->label('Slug')->sortable(),
                TextColumn::make('weekend_days')
                    ->label('Weekend Days')
                    ->formatStateUsing(fn ($state) => is_array($state) ? implode(', ', $state) : $state),
                TextColumn::make('check_in_time')->label('Check-in Time')->time(),
                TextColumn::make('check_out_time')->label('Check-out Time')->time(),
                TextColumn::make('created_at')->label('Created At')->dateTime(),
            ])
            
            ->filters([
                SelectFilter::make('company_id')
                    ->label('Company')
                    ->options(Company::all()->pluck('name_en', 'id')->toArray())
                    ->searchable(),

                Filter::make('weekend_days')
                    ->form([
                        Select::make('day')
                            ->label('Weekend Day')
                            ->options([
                                'Saturday' => 'Saturday',
                                'Sunday' => 'Sunday',
                                'Monday' => 'Monday',
                                'Tuesday' => 'Tuesday',
                                'Wednesday' => 'Wednesday',
                                'Thursday' => 'Thursday',
                                'Friday' => 'Friday',
                            ])
                            ->searchable()
                    ])
                    ->query(function (Builder $query, array $data) {
                        return $query->when($data['day'], function ($q) use ($data) {
                            return $q->whereJsonContains('weekend_days', $data['day']);
                        });
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
            'index' => Pages\ListCompanySchedules::route('/'),
            'create' => Pages\CreateCompanySchedule::route('/create'),
            'edit' => Pages\EditCompanySchedule::route('/{record}/edit'),
        ];
    }
}
