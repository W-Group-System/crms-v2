<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Contracts\Queue\ShouldQueue;

class ClosedMail extends Mailable
{
    use Queueable, SerializesModels;

    public $customerComplaint;

    public function __construct($customerComplaint)
    {
        $this->customerComplaint = $customerComplaint;
    }

    /**
     * Build the message.
     *
     * @return $this
     */
    public function build()
    {
        $email = $this->subject('Closure of Customer Complaint')
                    // $this->to($this->customerComplaint['Email'])
                    // ->cc(['international.sales@rico.com.ph', 'mrdc.sales@rico.com.ph', 'iad@wgroup.com.ph'])
                    // ->cc(['ict.engineer@wgroup.com.ph'])
                    // ->subject('Customer Complaint')
                    ->view('emails.closed')
                    ->with([
                        'CcNumber' => $this->customerComplaint['CcNumber'],
                        'DateComplaint' => $this->customerComplaint['created_at'],
                        'CompanyName' => $this->customerComplaint['CompanyName'],
                        'ContactName' => $this->customerComplaint['ContactName'],
                        'Email' => $this->customerComplaint['Email'],
                        'Telephone' => $this->customerComplaint['Telephone'],
                        'CustomerRemarks' => $this->customerComplaint['CustomerRemarks'],
                        'ComplaintCountry' => optional($this->customerComplaint->country)->Name ?? 'N/A',
                        'QualityClass' => $this->customerComplaint['QualityClass'],
                        'Department' => $this->customerComplaint['Department'],
                        'DateReceived' => $this->customerComplaint['DateReceived'],
                        'ReceivedBy' => optional($this->customerComplaint->users)->full_name ?? 'N/A',
                        'DateNoted' => $this->customerComplaint['DateNoted'],
                        'NotedBy' => optional($this->customerComplaint->noted_by)->full_name ?? 'N/A',
                        'ApprovedBy' => optional($this->customerComplaint->approved_by)->full_name ?? 'N/A',
                        'ImmediateAction' => $this->customerComplaint['ImmediateAction'],
                        'ObjectiveEvidence' => $this->customerComplaint['ObjectiveEvidence'],
                        'Investigation' => $this->customerComplaint['Investigation'],
                        'CorrectiveAction' => $this->customerComplaint['CorrectiveAction'],
                        'ActionObjectiveEvidence' => $this->customerComplaint['ActionObjectiveEvidence'],
                        'ActionResponsible' => optional($this->customerComplaint->action_responsible)->full_name ?? 'N/A',
                        'Acceptance' => $this->customerComplaint['Acceptance'],
                        'Claims' => $this->customerComplaint['Claims'],
                        'CnNumber' => $this->customerComplaint['CnNumber'],
                        'AmountIncurred' => $this->customerComplaint['AmountIncurred'],
                        'Shipment' => $this->customerComplaint['Shipment'],
                        'ShipmentCost' => $this->customerComplaint['ShipmentCost'],
                        'ShipmentDate' => $this->customerComplaint['ShipmentDate'],
                        'ClosedBy' => optional($this->customerComplaint->closed)->full_name ?? 'N/A',
                        'ClosedDate' => $this->customerComplaint['ClosedDate'],
                    ]);

        // Check if attachments exist and attach them
        // if (!empty($this->cc_attachments))
        // {
        //     foreach ($this->cc_attachments as $cc_attachment) {
        //         $filePath = storage_path('app/public/'.$cc_attachment);
        //         $email->attach($filePath);
        //     }
        // }

        return $email;
    }
}
