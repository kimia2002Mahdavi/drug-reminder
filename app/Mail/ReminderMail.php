<?php

namespace App\Mail;

use App\Models\Medication;
use App\Models\ReminderSchedule;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;

class ReminderMail extends Mailable
{
    use Queueable, SerializesModels;

    public $reminder;
    public $medication;

    /**
     * Create a new message instance.
     */
    public function __construct(ReminderSchedule $reminder, Medication $medication)
    {
        $this->reminder = $reminder;
        $this->medication = $medication;
    }

    /**
     * Get the message envelope.
     */
    public function envelope(): Envelope
    {
        return new Envelope(
            subject: 'Medication Reminder: ' . $this->medication->name,
        );
    }

    /**
     * Get the message content definition.
     */
    public function content(): Content
    {
        return new Content(
            view: 'emails.reminder', // آدرس Blade View
            with: [
                'medicationName' => $this->medication->name,
                'dosage' => $this->medication->dosage,
                'reminderTime' => $this->reminder->reminder_time,
                'message' => $this->reminder->message, // اگر message رو دارید
            ],
        );
    }

    /**
     * Get the attachments for the message.
     *
     * @return array<int, \Illuminate\Mail\Mailables\Attachment>
     */
    public function attachments(): array
    {
        return [];
    }
}