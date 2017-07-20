<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;
use App\Models\JoinRequests\EmailTemplate;
use Illuminate\Contracts\Queue\ShouldQueue;

class JoinRequestAcknowledged extends Mailable
{
    use Queueable, SerializesModels;

    /**
     * Email template model.
     *
     * @var App\Models\JoinRequests\EmailTemplate
     */
    public $template;

    /**
     * Create a new message instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->template = EmailTemplate::first();
    }

    /**
     * Build the message.
     *
     * @return $this
     */
    public function build()
    {
        return $this
            ->subject($this->template->subject)
            ->view('emails.join-acknowledgement');
    }
}
