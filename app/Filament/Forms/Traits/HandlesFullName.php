<?php

namespace App\Filament\Forms\Traits;

trait HandlesFullName
{
    protected function mutateFullName(array $data): array
    {
        [$en, $ar] = explode('/', $data['full_name'] . '/'); 
        $data['name'] = [
            'en' => trim($en),
            'ar' => trim($ar),
        ];
        unset($data['full_name']);
        return $data;
    }
}
