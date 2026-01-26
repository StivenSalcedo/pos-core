<?php

namespace App\Observers;

use App\Models\Audit;
use App\Models\ServicePayment;
use App\Models\ServiceProduct;
use App\Jobs\SendAuditDeletionNotification;

class AuditObserver
{
    /**
     * Handle the Audit "created" event.
     *
     * @param  \App\Models\Audit  $audit
     * @return void
     */
    public function created(Audit $audit)
    {
         // 1️⃣ Solo eliminaciones
        if ($audit->event !== 'deleted') {
            return;
        }

        // 2️⃣ Solo estas entidades
        if (!in_array($audit->auditable_type, [
            ServiceProduct::class,
            ServicePayment::class,
        ])) {
            return;
        }

        // 3️⃣ Disparar notificación
        SendAuditDeletionNotification::dispatch($audit);
    }

    /**
     * Handle the Audit "updated" event.
     *
     * @param  \App\Models\Audit  $audit
     * @return void
     */
    public function updated(Audit $audit)
    {
        //
    }

    /**
     * Handle the Audit "deleted" event.
     *
     * @param  \App\Models\Audit  $audit
     * @return void
     */
    public function deleted(Audit $audit)
    {
        // 2. Solo entidades objetivo
        if (! in_array($audit->parent_type, [
            ServicePayment::class,
            ServiceProduct::class,
        ])) {
            return;
        }

        // 3. Despachar job
        SendAuditDeletionNotification::dispatch($audit);
    }

    /**
     * Handle the Audit "restored" event.
     *
     * @param  \App\Models\Audit  $audit
     * @return void
     */
    public function restored(Audit $audit)
    {
        //
    }

    /**
     * Handle the Audit "force deleted" event.
     *
     * @param  \App\Models\Audit  $audit
     * @return void
     */
    public function forceDeleted(Audit $audit)
    {
        //
    }
}
