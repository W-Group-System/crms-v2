<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Contracts\Queue\ShouldQueue;

class AssignCcDepartmentMail extends Mailable
{
    use Queueable, SerializesModels;
    
    public $customerComplaint;
    public $cc_attachments;
    public $showButton;

    /**
     * Create a new message instance.
     *
     * @return void
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
        $email = $this->subject('New Customer Complaint Assignment')
                    ->view('emails.assign_cc_department')
                    ->with([
                        'customerComplaint' => $this->customerComplaint,
                        'attachments' => $this->cc_attachments,
                        'ConcernedName' => optional($this->customerComplaint->concerned)->Name,
                        'showButton' => $this->showButton, // pass to view
                    ]);

        // if ($this->customerComplaint->NcarIssuance == 1) {
        //     $email->cc('mika.trinidad@rico.com.ph');
        // }

        // Attach uploaded files
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
