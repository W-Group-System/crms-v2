<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Contracts\Queue\ShouldQueue;

class CustomerComplaintMail extends Mailable
{
    use Queueable, SerializesModels;

    public $customerComplaint;
    public $cc_attachments;
    public $showButton;

    /**
     * Create a new message instance.
     */
    public function __construct($customerComplaint, $cc_attachments, $showButton = false)
    {
        $this->customerComplaint = $customerComplaint;
        $this->cc_attachments = $cc_attachments;
        $this->showButton = $showButton;
    }

    /**
     * Build the message.
     *
     * @return $this
     */
    public function build()
    {
        $email = $this->subject('Customer Complaint')
                    // $this->to($this->customerComplaint['Email'])
                    // ->cc(['international.sales@rico.com.ph', 'mrdc.sales@rico.com.ph', 'iad@wgroup.com.ph'])
                    // ->cc(['ict.engineer@wgroup.com.ph'])
                    // ->subject('Customer Complaint')
                    ->view('emails.customer_complaint')
                    ->with([
                        'CcNumber' => $this->customerComplaint['CcNumber'],
                        'CompanyName' => $this->customerComplaint['CompanyName'],
                        'ContactName' => $this->customerComplaint['ContactName'],
                        'Email' => $this->customerComplaint['Email'],
                        'Telephone' => $this->customerComplaint['Telephone'],
                        'CustomerRemarks' => $this->customerComplaint['CustomerRemarks'],
                        'ComplaintCountry' => optional($this->customerComplaint->country)->Name ?? 'N/A',
                        'button_text' => 'Visit Customer Complaint',
                        'button_url' => url('/cc_list?open=10'),
                        'showButton' => $this->showButton, // pass to view
                    ]);

        // Check if attachments exist and attach them
        if (!empty($this->cc_attachments))
        {
            foreach ($this->cc_attachments as $cc_attachment) {
                $filePath = storage_path('app/public/'.$cc_attachment);
                $email->attach($filePath);
            }
        }

        return $email;
    }

}
