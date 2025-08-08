<?php

namespace App\Console\Commands;

use App\User;
use App\CustomerComplaint2;
use App\Notifications\CcNotif;
use Illuminate\Console\Command;

class ccStatus extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'command:cc_status';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Command description';

    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct()
    {
        parent::__construct();
    }

    /**
     * Execute the console command.
     *
     * @return mixed
     */
    public function handle()
    {
        info("START GET REPORTS");
        $users = User::where('department_id', 1)->where('is_active', 1)->get();
        
        foreach($users as $user)
        {
            $table = "<table style='margin-bottom:10px;' width='100%' border='1' cellspacing=0><tr><th>CCF #</th><th>Customer Remarks</th><th>Date Complaint</th></tr>";
            $complaints = CustomerComplaint2::where('Status', '10')->orderBy('created_at', 'asc')->get();
            
            foreach($complaints as $complaint)
            {
                if($complaint->created_at < date('Y-m-d'))
                {
                    $status = " style='background-color:Tomato;color:white;'";
                }
                else
                {
                    $status = "";
                }
                $table .= "<tr ".$status."><td style='width:30%;' align='center'>".$complaint->CcNumber."</td><td style='width:40%;' align='center'>".$complaint->CustomerRemarks."</td><td style='width:30%;' align='center'>".date('Y-m-d',strtotime($complaint->created_at))."</td></tr>";
            }
            $table .= "</table>";
            if($complaints->count() > 0)
            {
                $user->notify(new CcNotif($table));
            }
        }
       
        
        return "success";
    }
}
