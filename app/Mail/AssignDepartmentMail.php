<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Contracts\Queue\ShouldQueue;

class AssignDepartmentMail extends Mailable
{
    use Queueable, SerializesModels;
    
    public $customerSatisfaction;
    public $cs_attachments;

    public function __construct($customerSatisfaction, $cs_attachments)
    {
        $this->customerSatisfaction = $customerSatisfaction;
        $this->cs_attachments = $cs_attachments;
    }

    public function build()
    {
        $email = $this->subject('New Customer Satisfaction Assignment')
                    ->view('emails.assign_department')
                    ->with([
                        'customerSatisfaction' => $this->customerSatisfaction,
                        'attachments' => $this->cs_attachments,
                        'ConcernedName' => optional($this->customerSatisfaction->concerned)->Name
                    ]);

        // Attach uploaded files
        if (!empty($this->cs_attachments))
        {
            foreach ($this->cs_attachments as $cs_attachment) {
                $filePath = storage_path('app/public/'.$cs_attachment);
                $email->attach($filePath);
            }
        }

        return $email;
    }
}
