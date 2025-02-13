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

    /**
     * Create a new message instance.
     */
    public function __construct($customerComplaint, $cc_attachments)
    {
        $this->customerComplaint = $customerComplaint;
        $this->cc_attachments = $cc_attachments;
    }

    /**
     * Build the message.
     *
     * @return $this
     */
    public function build()
    {
        $email = $this->to($this->customerComplaint['Email'])
                    ->cc(['ict.engineer@wgroup.com.ph', 'schultzxhenry@gmail.com'])
                    ->subject('Customer Complaint Form Submission')
                    ->view('emails.customer_complaint')
                    ->with([
                        'CcNumber' => $this->customerComplaint['CcNumber'],
                        'CompanyName' => $this->customerComplaint['CompanyName'],
                        'ContactName' => $this->customerComplaint['ContactName'],
                        'Email' => $this->customerComplaint['Email'],
                        'Telephone' => $this->customerComplaint['Telephone'],
                        'CustomerRemarks' => $this->customerComplaint['CustomerRemarks'],
                        'ComplaintCountry' => optional($this->customerComplaint->country)->Name ?? 'N/A',
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
