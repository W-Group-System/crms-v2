<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class CustomerSatisfactionMail extends Mailable
{
    use Queueable, SerializesModels;

    public $customerSatisfaction;

    /**
     * Create a new message instance.
     */
    public function __construct($customerSatisfaction)
    {
        $this->customerSatisfaction = $customerSatisfaction;
    }

    /**
     * Build the message.
     */
    public function build()
    {
        return $this->to($this->customerSatisfaction['Email'])
                    ->cc(['international.sales@rico.com.ph', 'mrdc.sales@rico.com.ph', 'iad@wgroup.com.ph'])
                    ->subject('Customer Satisfaction Form Submission')
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
                    ]);
    }

}
