<?php

namespace App\Support;

trait Notifies
{
    protected function notify(string $type, string $message): array
    {
        return ['type' => $type, 'message' => $message];
    }

    /**
     * Decide sucesso/erro com base no número de linhas afetadas
     */
    protected function handleAffected(
        int $affected,
        string $successRoute,
        string $successMessage,
        string $errorMessage
    ) {
        if ($affected > 0) {
            return redirect()
                ->route($successRoute)
                ->with('notify', $this->notify('success', $successMessage));
        }

        return redirect()
            ->back()
            ->withInput()
            ->with('notify', $this->notify('warning', $errorMessage));
    }

    /**
     * Para erros inesperados (exceptions)
     */
    protected function handleException(string $message)
    {
        return redirect()
            ->back()
            ->withInput()
            ->with('notify', $this->notify('danger', $message));
    }
}