<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Contracts\Queue\ShouldQueue;

class AcknowledgedMail extends Mailable
{
    use Queueable, SerializesModels;

    public $customerSatisfaction;
    public $cs_attachments;

    /**
     * Create a new message instance.
     *
     * @return void
     */
    public function __construct($customerSatisfaction, $cs_attachments)
    {
        $this->customerSatisfaction = $customerSatisfaction;
        $this->cs_attachments = $cs_attachments;
    }

    /**
     * Build the message.
     *
     * @return $this
     */
    public function build()
    {
        $email_acknowledged = $this->subject('Customer Satisfaction')
                    // $this->to($this->customerSatisfaction['Email'])
                    // ->cc(['international.sales@rico.com.ph', 'mrdc.sales@rico.com.ph', 'iad@wgroup.com.ph'])
                    // ->cc(['ict.engineer@wgroup.com.ph'])
                    // ->subject('Customer Satisfaction Form Submission')
                    ->view('emails.acknowledged')
                    ->with([
                        'CsNumber' => $this->customerSatisfaction['CsNumber'],
                        'CompanyName' => $this->customerSatisfaction['CompanyName'],
                        'ContactName' => $this->customerSatisfaction['ContactName'],
                        'Concerned' => $this->customerSatisfaction['Concerned'],
                        'Description' => $this->customerSatisfaction['Description'],
                        'CategoryName' => optional($this->customerSatisfaction->category)->Name, 
                        'ContactNumber' => $this->customerSatisfaction['ContactNumber'],
                        'Email' => $this->customerSatisfaction['Email'],
                        'ApprovedBy' => $this->customerSatisfaction->approvedBy->full_name, 
                        'ApprovedDate' => $this->customerSatisfaction['ApprovedDate'],
                    ]);

        // Check if attachments exist and attach them
        if (!empty($this->cs_attachments))
        {
            foreach ($this->cs_attachments as $cs_attachment) {
                $filePath = storage_path('app/public/'.$cs_attachment);
                $email_acknowledged->attach($filePath);
            }
        }

        return $email_acknowledged;
    }
}
