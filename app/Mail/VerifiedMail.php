<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Contracts\Queue\ShouldQueue;

class VerifiedMail extends Mailable
{
    use Queueable, SerializesModels;

    public $verifiedComplaint;
    public $verification;
    public $showButton;

    /**
     * Create a new message instance.
     *
     * @return void
     */
    public function __construct($verifiedComplaint, $verification, $showButton = false)
    {
        $this->verifiedComplaint = $verifiedComplaint;
        $this->verification = $verification;
        $this->showButton = $showButton;
    }

    /**
     * Build the message.
     *
     * @return $this
     */
    public function build()
    {
        $email_verified = $this->subject('Verified Customer Complaint')
                    ->view('emails.verified')
                    ->with([
                        'CcNumber' => $this->verifiedComplaint['CcNumber'],
                        'Acceptance' => $this->verifiedComplaint['Acceptance'],
                        'Claims' => $this->verifiedComplaint['Claims'],
                        'Shipment' => $this->verifiedComplaint['Shipment'],
                        'CnNumber' => $this->verifiedComplaint['CnNumber'],
                        'ShipmentDate' => $this->verifiedComplaint['ShipmentDate'],
                        'AmountIncurred' => $this->verifiedComplaint['AmountIncurred'],
                        'ShipmentCost' => $this->verifiedComplaint['ShipmentCost'],
                        'ConcernedName' => optional($this->verifiedComplaint->concerned)->Name,
                        'showButton' => $this->showButton, // pass to view
                    ]);

        if (!empty($this->verification))
        {
            foreach ($this->verification as $verification_attachment) {
                $filePath = storage_path('app/public/'.$verification_attachment);
                $email_verified->attach($filePath);
            }
        }

        return $email_verified;
    }
}
