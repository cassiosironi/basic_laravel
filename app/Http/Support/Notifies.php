<?php

namespace App\Support;

trait Notifies
{
    
    protected function notify(string $type, string $message): array
    {
        return ['type' => $type, 'message' => $message];
    }

    protected function redirectNotify(string $routeName, string $type, string $message)
    {
        return redirect()
            ->route($routeName)
            ->with('notify', $this->notify($type, $message));
    }

    protected function backNotify(string $type, string $message)
    {
        return redirect()
            ->back()
            ->withInput()
            ->with('notify', $this->notify($type, $message));
    }

    // seu handleAffected continua válido para CRUD
    protected function handleAffected(int $affected, string $successRoute, string $successMessage, string $errorMessage)
    {
        if ($affected > 0) {
            return $this->redirectNotify($successRoute, 'success', $successMessage);
        }
        return $this->backNotify('warning', $errorMessage);
    }

    protected function handleException(string $message)
    {
        return $this->backNotify('danger', $message);
    }

}