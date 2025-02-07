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
    public $attachments;

    public function __construct($customerSatisfaction, $attachments)
    {
        $this->customerSatisfaction = $customerSatisfaction;
        $this->attachments = $attachments;
    }

    public function build()
    {
        $email = $this->subject('New Customer Satisfaction Assignment')
                      ->view('emails.assign_department')
                      ->with([
                          'customerSatisfaction' => $this->customerSatisfaction,
                          'attachments' => $this->attachments,
                          'ConcernedName' => optional($this->customerSatisfaction->concerned)->Name
                      ]);

        // Attach uploaded files
        foreach ($this->attachments as $attachment) {
            $email->attach($attachment);
        }

        return $email;
    }
}
