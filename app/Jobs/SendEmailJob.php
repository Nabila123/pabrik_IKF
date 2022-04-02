<?php

namespace App\Jobs;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldBeUnique;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use App\Mail\Gmail;
use Illuminate\Support\Facades\Mail;
use App\Models\GudangPackingRekapDetail;
use Carbon\Carbon;

class SendEmailJob implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    /**
     * Create a new job instance.
     *
     * @return void
     */
    public function __construct()
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
        $packings = GudangPackingRekapDetail::whereDate('updated_at', Carbon::today())->get();
        $packingBaju = [];
        $ukuranBaju = [];
        foreach ($packings as $key => $packing) {
            if(!array_key_exists($packing->ukuranBaju, $ukuranBaju)){
                $ukuranBaju[$packing->ukuranBaju]['ukuran'] = $packing->ukuranBaju;
                $ukuranBaju[$packing->ukuranBaju]['jumlah'] = 1;
            }else{
                $ukuranBaju[$packing->ukuranBaju]['jumlah'] += 1;
            }
            $packingBaju[$packing->jenisBaju] = $ukuranBaju;
        }

        Mail::to('nuramalina.0296@gmail.com')->send(new Gmail($packingBaju));
    }
}
