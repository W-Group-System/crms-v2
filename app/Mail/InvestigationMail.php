<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Contracts\Queue\ShouldQueue;

class InvestigationMail extends Mailable
{
    use Queueable, SerializesModels;

    public $investigationComplaint;
    public $objective;
    public $showButton;

    /**
     * Create a new message instance.
     *
     * @return void
     */
    public function __construct($investigationComplaint, $objective, $showButton = false)
    {
        $this->investigationComplaint = $investigationComplaint;
        $this->objective = $objective;
        $this->showButton = $showButton;
    }

    /**
     * Build the message.
     *
     * @return $this
     */
    public function build()
    {
        $email = $this->subject('Investigation Notice')
                    ->view('emails.investigation')
                    ->with([
                        'CcNumber' => $this->investigationComplaint['CcNumber'],
                        'ImmediateAction' => $this->investigationComplaint['ImmediateAction'],
                        'ObjectiveEvidence' => $this->investigationComplaint['ObjectiveEvidence'],
                        'Investigation' => $this->investigationComplaint['Investigation'],
                        'CorrectiveAction' => $this->investigationComplaint['CorrectiveAction'],
                        'ActionObjectiveEvidence' => $this->investigationComplaint['ActionObjectiveEvidence'],
                        'ContactName' => $this->investigationComplaint['ContactName'],
                        'data' => $this->investigationComplaint,
                        // 'button_text' => 'Visit Customer Complaint',
                        // 'button_url' => url('/cc_list?open=10'),
                        // 'showButton' => $this->showButton, // pass to view
                    ]);

        if (!empty($this->objective))
        {
            foreach ($this->objective as $investigation_attachment) {
                $filePath = storage_path('app/public/'.$investigation_attachment);
                $email->attach($filePath);
            }
        }

        return $email;
    }
}
