<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Mail\OrderCompleted;
use Illuminate\Support\Facades\Mail;

class SendSystemEmails extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'superlocales:send-system-emails';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Command description';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        //

        $datos = ['nombre' => 'Rodolfo'];
        Mail::to('destinatario@example.com')->send(new OrderCompleted($datos));
        return "Correo enviado exitosamente";




    }
}
