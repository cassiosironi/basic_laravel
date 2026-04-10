<?php

namespace App\Support;

trait Notifies
{
    

    /**
     * Padroniza o payload da notificação
     * (mesma estrutura usada no logout)
     */
    protected function notify(string $type, string $message): array
    {
        return [
            'type' => $type,
            'message' => $message,
        ];
    }

    /**
     * Volta para a página anterior com notificação
     * (usa SOMENTE métodos nativos do Laravel)
     */
    protected function backNotify(string $type, string $message)
    {
        return redirect()
            ->back()
            ->withInput()
            ->with('notify', $this->notify($type, $message));
    }

    /**
     * Redireciona para uma rota com notificação
     * (equivalente ao padrão usado no logout)
     */
    protected function redirectNotify(string $route, string $type, string $message)
    {
        return redirect()
            ->route($route)
            ->with('notify', $this->notify($type, $message));
    }

    /**
     * Decide sucesso ou erro com base no número de linhas afetadas
     * (CREATE / UPDATE / DELETE)
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
            ->with('notify', $this->notify('danger', $errorMessage));
    }

}