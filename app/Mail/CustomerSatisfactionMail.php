<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class CustomerSatisfactionMail extends Mailable
{
    use Queueable, SerializesModels;

    public $customerSatisfaction;
    public $customer_attachments;
    public $showButton;

    /**
     * Create a new message instance.
     */
    public function __construct($customerSatisfaction, $customer_attachments, $showButton = false)
    {
        $this->customerSatisfaction = $customerSatisfaction;
        $this->customer_attachments = $customer_attachments;
        $this->showButton = $showButton;
    }

    /**
     * Build the message.
     */
    public function build()
    {   
        $email_cs = $this->subject('Customer Satisfaction')
                    // $this->to($this->customerSatisfaction['Email'])
                    // ->cc(['international.sales@rico.com.ph', 'mrdc.sales@rico.com.ph', 'iad@wgroup.com.ph'])
                    // ->cc(['ict.engineer@wgroup.com.ph'])
                    // ->subject('Customer Satisfaction Form Submission')
                    ->view('emails.customer_satisfaction')
                    ->with([
                        'CsNumber' => $this->customerSatisfaction['CsNumber'],
                        'CompanyName' => $this->customerSatisfaction['CompanyName'],
                        'ContactName' => $this->customerSatisfaction['ContactName'],
                        'Concerned' => $this->customerSatisfaction['Concerned'],
                        'Description' => $this->customerSatisfaction['Description'],
                        'CategoryName' => optional($this->customerSatisfaction->category)->Name, 
                        'ContactNumber' => $this->customerSatisfaction['ContactNumber'],
                        'Email' => $this->customerSatisfaction['Email'],
                        'button_text' => 'Visit Customer Satisfaction',
                        'button_url' => url('/cs_list'),
                        'showButton' => $this->showButton, // pass to view
                    ]);

        // Check if attachments exist and attach them
        if (!empty($this->customer_attachments))
        {
            foreach ($this->customer_attachments as $cs_attachment) {
                $filePath = storage_path('app/public/'.$cs_attachment);
                $email_cs->attach($filePath);
            }
        }

        return $email_cs;
    }

}
