<?php

namespace App\Jobs;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldBeUnique;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use App\Models\Audit;



use App\Services\Audit\AuditMessageService;
use App\Mail\AuditDeletionMail;
use Illuminate\Support\Facades\Mail;

class SendAuditDeletionNotification implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    /**
     * Create a new job instance.
     *
     * @return void
     */
    public function __construct(public Audit $audit)
    {
        //
    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle()
    {
        // 👇 reutilizamos lo que YA tienes
        $messages = AuditMessageService::message($this->audit);

        if (empty($messages)) {
            return;
        }

        $emails = config('audit.deletion_emails');

        if (empty($emails)) {
            return;
        }

        Mail::to($emails)
            ->send(new AuditDeletionMail($this->audit, $messages));
    }
}
