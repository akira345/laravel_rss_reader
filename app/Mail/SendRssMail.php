<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Contracts\Queue\ShouldQueue;

class SendRssMail extends Mailable
{
    use Queueable, SerializesModels;

    public $mailto, $subject, $contents, $mailfrom;
    /**
     * Create a new message instance.
     *
     * @return void
     */
    public function __construct($subject, $contents, $mailfrom)
    {
        $this->subject = $subject;
        $this->contents = $contents;
        $this->mailfrom = $mailfrom;
    }

    /**
     * Build the message.
     *
     * @return $this
     */
    public function build()
    {
        return $this->from($this->mailfrom)
            ->subject($this->subject)
            ->text('emails.sendrss')
            ->with([
                'contents' => $this->contents,
            ]);
    }
}
