<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;

class OrderCompleted extends Mailable
{
    use Queueable, SerializesModels;
    public $Order;


    /**
     * Create a new message instance.
     */
    public function __construct($Order )
    {
        //
        $this->Order = $Order;
    }

    /**
     * Get the message envelope.
     */
    public function envelope(): Envelope
    {
        return new Envelope(
            subject: 'Pago de Orden ' . $this->Order->id . ' confirmado',
        );
    }

    /**
     * Get the message content definition.
     */
    public function content(): Content
    {
        return new Content(
            view: 'emails.orders.completed',
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

     public function build()
    {


        //$this->data["nombre"] = "Rodolfo Gonzalez :) ";
        return $this->view('emails.orders.completed')
                    ->subject('Asunto del Correo')
                    ->with('order', $this->Order, );
    }
}
