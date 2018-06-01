<?php

namespace App\Jobs;

use App\Http\Controllers\Admin\ImportController;
use App\Model\Teacher;
use Illuminate\Bus\Queueable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Session;

class ObtainCourse implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;
    public $tries = 3;
    protected $id;
    protected $code;
    /**
     * Create a new job instance.
     *
     * @return void
     */
    public function __construct($id,$code)
    {
        $this->id = $id;
        $this->code = $code;
    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle()
    {
        $r=Teacher::getCourse($this->id,$this->code);
        if (!is_array($r)){
            Log::info("教师id"."$this->id"."=======".$r);
        }

    }


}
