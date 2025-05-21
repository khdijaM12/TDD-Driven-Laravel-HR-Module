<?php

namespace App\Filament\Resources;

use App\Filament\Resources\CompanyBranchResource\Pages;
use App\Filament\Resources\CompanyBranchResource\RelationManagers;
use App\Models\CompanyBranch;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Tables\Columns\TextColumn;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;
use Filament\Tables\Filters\Filter;
use Filament\Tables\Filters\SelectFilter;
use Filament\Forms\Components\DatePicker;
use App\Models\Company;

class CompanyBranchResource extends Resource
{
    protected static ?string $model = CompanyBranch::class;

    protected static ?string $navigationIcon = 'heroicon-o-building-office';

    public static function form(Form $form): Form
    {
        return $form
           ->schema([
                Select::make('company_id')
                    ->label('Company')
                    ->required()
                    ->options(Company::all()->pluck('name_en', 'id')->toArray())
                    ->searchable(),

                TextInput::make('name_en')
                    ->label('English Name')
                    ->required()
                    ->maxLength(255),

                TextInput::make('name_ar')
                    ->label('Arabic Name')
                    ->required()
                    ->maxLength(255),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('id')->sortable(),
                TextColumn::make('company.name_en')->label('Company (EN)')->searchable(),
                TextColumn::make('name_en')->label('Branch Name (EN)')->searchable(),
                TextColumn::make('name_ar')->label('Branch Name (AR)')->searchable(),
                TextColumn::make('created_at')->label('Created At')->dateTime(),
            ])
            ->filters([
            SelectFilter::make('company_id')
                ->label('Company')
                ->options(Company::all()->pluck('name_en', 'id')->toArray())
                ->searchable(),

            Filter::make('name_en')
                ->label('Branch Name (EN)')
                ->form([
                    TextInput::make('name_en'),
                ])
                ->query(function (Builder $query, array $data) {
                    return $query->when(
                        $data['name_en'],
                        fn ($q, $name) => $q->where('name_en', 'like', "%{$name}%")
                    );
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
            'index' => Pages\ListCompanyBranches::route('/'),
            'create' => Pages\CreateCompanyBranch::route('/create'),
            'edit' => Pages\EditCompanyBranch::route('/{record}/edit'),
        ];
    }
}
