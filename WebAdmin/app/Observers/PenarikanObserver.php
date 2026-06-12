<?php

namespace App\Observers;

use App\Models\Penarikan;

class PenarikanObserver
{
    public function created(Penarikan $penarikan): void
    {
        $this->clearSaldoCache($penarikan->nasabah_id);
    }

    public function updated(Penarikan $penarikan): void
    {
        $this->clearSaldoCache($penarikan->nasabah_id);

        if ($penarikan->isDirty('nasabah_id')) {
            $this->clearSaldoCache($penarikan->getOriginal('nasabah_id'));
        }
    }

    public function deleted(Penarikan $penarikan): void
    {
        $this->clearSaldoCache($penarikan->nasabah_id);
    }

    protected function clearSaldoCache(int $nasabahId): void
    {
        \Illuminate\Support\Facades\Cache::forget("nasabah:{$nasabahId}:saldo");
        \Illuminate\Support\Facades\Cache::forget("nasabah:{$nasabahId}:statistik");
    }
}
