<?php
declare(strict_types=1);

namespace Module\Order\Jobs;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Notification;
use Module\Order\Notifications\DegradedIngredients;

class NotifyDegradedIngredients implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    /**
     * Create a new job instance.
     *
     * @return void
     */
    public function __construct(private array $downStandardIngredients)
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
        $ingredientInfoString = 'Those ingredients quantity are degraded to below standards:<br/> ';
        foreach($this->downStandardIngredients as $ingredient){
            $ingredientInfoString .= $ingredient['name'].' ';
        }
        dispatch(function () use ($ingredientInfoString){
            Notification::route('mail', 'admin@food-app.com')
                ->notify(new DegradedIngredients($ingredientInfoString));
        });

    }
}
