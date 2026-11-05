<?php

declare(strict_types=1);

namespace App\Http\Controllers\Site;

use App\Enums\EnquiryType;
use App\Http\Controllers\Controller;
use App\Http\Requests\Site\SubmitProductEnquiryRequest;
use App\Services\EnquiryService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\RedirectResponse;

final class ProductEnquiryController extends Controller
{
    public const SUCCESS_MESSAGE = 'Thank you! Your enquiry has been sent. We will get back to you shortly.';

    public function store(SubmitProductEnquiryRequest $request, EnquiryService $enquiries): RedirectResponse|JsonResponse
    {
        // Bots that fill the honeypot get the normal success response, so they learn nothing.
        if (! $request->isSpam()) {
            $enquiries->submit(
                EnquiryType::Product,
                $request->enquiryFields(),
                $request->product(),
                $request->ip(),
                $request->userAgent(),
            );
        }

        return $request->expectsJson()
            ? response()->json(['message' => self::SUCCESS_MESSAGE], 201)
            : back()->with('enquiry_status', self::SUCCESS_MESSAGE);
    }
}
