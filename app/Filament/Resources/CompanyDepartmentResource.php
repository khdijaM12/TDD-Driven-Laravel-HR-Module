<?php

namespace App\Filament\Resources;

use App\Filament\Resources\CompanyDepartmentResource\Pages;
use App\Filament\Resources\CompanyDepartmentResource\RelationManagers;
use App\Models\CompanyDepartment;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Filters\SelectFilter;
use Filament\Tables\Filters\Filter;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;
use App\Models\Company;

class CompanyDepartmentResource extends Resource
{
    protected static ?string $model = CompanyDepartment::class;

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
                TextColumn::make('name_en')->label('Department Name (EN)')->searchable(),
                TextColumn::make('name_ar')->label('Department Name (AR)')->searchable(),
                TextColumn::make('created_at')->label('Created At')->dateTime(),
            ])
            ->filters([
            SelectFilter::make('company_id')
                ->label('Company')
                ->options(Company::all()->pluck('name_en', 'id')->toArray())
                ->searchable(),

            Filter::make('name_en')
                ->label('Department Name (EN)')
                ->form([
                    TextInput::make('name_en'),
                ])
                ->query(function (Builder $query, array $data) {
                    return $query->when(
                        $data['name_en'],
                        fn ($q, $name) => $q->where('name_en', 'like', "%{$name}%")
                    );
                }),

            Filter::make('name_ar')
                ->label('Department Name (AR)')
                ->form([
                    TextInput::make('name_ar'),
                ])
                ->query(function (Builder $query, array $data) {
                    return $query->when(
                        $data['name_ar'],
                        fn ($q, $name) => $q->where('name_ar', 'like', "%{$name}%")
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
            'index' => Pages\ListCompanyDepartments::route('/'),
            'create' => Pages\CreateCompanyDepartment::route('/create'),
            'edit' => Pages\EditCompanyDepartment::route('/{record}/edit'),
        ];
    }
}
