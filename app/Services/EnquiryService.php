<?php

declare(strict_types=1);

namespace App\Services;

use App\Enums\EnquiryStatus;
use App\Enums\EnquiryType;
use App\Enums\SiteSettingKey;
use App\Mail\EnquiryReceivedMail;
use App\Models\Enquiry;
use App\Models\Product;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Mail;
use Throwable;

/**
 * Saves enquiries sent from the website and emails them to the address set in
 * Site settings. The enquiry is always saved first, so a mail failure never
 * loses a lead.
 */
final class EnquiryService
{
    public function __construct(private readonly SiteSettingService $settings) {}

    /**
     * @param  array<string, string|null>  $fields  validated visitor fields
     */
    public function submit(
        EnquiryType $type,
        array $fields,
        ?Product $product,
        ?string $ipAddress,
        ?string $userAgent,
    ): Enquiry {
        $enquiry = new Enquiry($fields);
        $enquiry->forceFill([
            'type' => $type,
            'status' => EnquiryStatus::New,
            'product_id' => $product?->id,
            'product_name' => $product?->name,
            'ip_address' => $ipAddress,
            'user_agent' => $userAgent === null ? null : mb_substr($userAgent, 0, 255),
        ])->save();

        $this->notifyAdmin($enquiry);

        return $enquiry;
    }

    private function notifyAdmin(Enquiry $enquiry): void
    {
        $recipient = $this->settings->get(SiteSettingKey::EnquiryNotificationEmail);

        if ($recipient === null) {
            return;
        }

        try {
            Mail::to($recipient)->send(new EnquiryReceivedMail($enquiry));
        } catch (Throwable $exception) {
            Log::error('Enquiry notification email could not be sent', [
                'enquiry_id' => $enquiry->id,
                'error' => $exception->getMessage(),
            ]);
        }
    }
}
