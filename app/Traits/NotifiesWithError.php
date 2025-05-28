<?php

namespace App\Traits;

use Filament\Notifications\Notification;

trait NotifiesWithError
{
    protected function notifyError(string $message): void
    {
        Notification::make()
            ->title('Error')
            ->body($message)
            ->danger()
            ->send();

        if (method_exists($this, 'halt')) {
            $this->halt();
        }
    }
}
