<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;
use App\Models\JoinRequests\JoinRequest;
use Illuminate\Contracts\Queue\ShouldQueue;

class JoinRequestStatus extends Mailable
{
    use Queueable, SerializesModels;

    /**
     * Join Request model.
     *
     * @var App\Models\JoinRequests\JoinRequest
     */
    public $jr;

    /**
     * Subject string.
     *
     * @var string
     */
    public $subject;

    /**
     * Content string.
     *
     * @var string
     */
    public $body;

    /**
     * Create a new message instance.
     *
     * @return void
     */
    public function __construct(JoinRequest $jr, string $subject, string $body)
    {
        $this->jr = $jr;
        $this->subject = $subject;
        $this->body = $body;
    }

    /**
     * Build the message.
     *
     * @return $this
     */
    public function build()
    {
        return $this
            ->subject($this->subject)
            ->view('emails.join-status');
    }
}
