<?php

namespace App\Filament\Resources;

use App\Filament\Resources\CompanyJobResource\Pages;
use App\Filament\Resources\CompanyJobResource\RelationManagers;
use App\Models\CompanyJob;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;
use Filament\Tables\Filters\SelectFilter;
use Filament\Tables\Filters\Filter;
use App\Models\Company;
use Filament\Facades\Filament;
use Filament\Forms\Components\Hidden;

class CompanyJobResource extends Resource
{
    protected static ?string $model = CompanyJob::class;

    protected static ?string $navigationIcon = 'heroicon-o-clipboard-document-check';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Select::make('company_id')
                    ->label('Company')
                    ->required()
                    ->options(fn () => static::getCompanyOptions())
                    ->default(fn () => static::getDefaultCompanyId())
                    ->disabled(fn () => static::isCompanyFieldDisabled())
                    ->searchable(),

                Hidden::make('company_id')
                    ->default(fn () => static::isCompanyAuth() ? auth('company')->user()->company_id : null)
                    ->dehydrated(fn () => static::isCompanyAuth()),

                TextInput::make('name_en')
                    ->label('Job Title (EN)')
                    ->required()
                    ->maxLength(255),

                TextInput::make('name_ar')
                    ->label('Job Title (AR)')
                    ->required()
                    ->maxLength(255),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('id')->sortable(),
                TextColumn::make('company.name_en')->label('Company'),
                TextColumn::make('name_en')->label('Job Title (EN)')->searchable(),
                TextColumn::make('name_ar')->label('Job Title (AR)')->searchable(),
                TextColumn::make('created_at')->label('Created At')->dateTime(),
            ])
            ->filters([
                SelectFilter::make('company_id')
                    ->label('Company')
                    ->options(
                        Company::all()->pluck('name_en', 'id')->toArray()
                    )
                    ->searchable(),
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
            'index' => Pages\ListCompanyJobs::route('/'),
            'create' => Pages\CreateCompanyJob::route('/create'),
            'edit' => Pages\EditCompanyJob::route('/{record}/edit'),
        ];
    }

    private static function getCompanyOptions(): array
    {
        if (Filament::getCurrentPanel()->getId() === 'company') {
            return Company::where('id', auth('company')->user()->company_id)
                ->get()
                ->mapWithKeys(fn($company) => [$company->id => $company->name_en])
                ->toArray();
        }
        return Company::all()
            ->mapWithKeys(fn($company) => [$company->id => $company->name_en])
            ->toArray();
    }

    private static function getDefaultCompanyId(): ?int
    {
        return Filament::getCurrentPanel()->getId() === 'company'
            ? auth('company')->user()->company_id
            : null;
    }

    private static function isCompanyFieldDisabled(): bool
    {
        return Filament::getCurrentPanel()->getId() === 'company';
    }

    private static function isCompanyAuth(): bool
    {
        return auth()->guard('company')->check();
    }
}
