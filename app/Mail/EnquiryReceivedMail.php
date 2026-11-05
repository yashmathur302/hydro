<?php

declare(strict_types=1);

namespace App\Mail;

use App\Models\Enquiry;
use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Address;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;

/**
 * Notifies the site owner about a new enquiry. Reply-To is the visitor,
 * so the owner can answer straight from the inbox.
 */
final class EnquiryReceivedMail extends Mailable
{
    use Queueable;
    use SerializesModels;

    public function __construct(public readonly Enquiry $enquiry) {}

    public function envelope(): Envelope
    {
        $subject = $this->enquiry->product_name
            ? "New enquiry: {$this->enquiry->product_name}"
            : 'New '.mb_strtolower($this->enquiry->type->label()).' from '.$this->enquiry->name;

        return new Envelope(
            replyTo: $this->enquiry->email ? [new Address($this->enquiry->email, $this->enquiry->name)] : [],
            subject: $subject,
        );
    }

    public function content(): Content
    {
        return new Content(
            view: 'mail.enquiry-received',
            text: 'mail.enquiry-received-text',
            with: ['adminUrl' => route('admin.enquiries.show', $this->enquiry)],
        );
    }
}
