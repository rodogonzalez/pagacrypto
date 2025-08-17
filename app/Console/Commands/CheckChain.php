<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Helpers\WalletObserver;

class CheckChain extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'superlocal:check-chain {wallet=LZCqkNsp89vrD66Yw13QsBVL5ZnDomg5xw} {currency=ltc}';

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
        $wallet_helper = new WalletObserver();
        $wallet = env('LTC_BLOCKBEE_WALLET_ADDRES');
        // ="";
        $wallet = $this->argument('wallet');
        $currency= $this->argument('currency');  //"ltc";

        $this->info('Exploring  ' .$currency . " blockchain" ) ;
        $this->info('Checking transactions on : ' . $wallet  . ' address') ;




        $wallet_status    = $wallet_helper->getAddressData($wallet, $currency, true);
        dd($wallet_status);
        dd(count( $wallet_status));
        dd($wallet_status->data[0]->transactionLists);

    }
}
