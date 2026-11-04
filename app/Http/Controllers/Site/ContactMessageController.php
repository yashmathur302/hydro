<?php

declare(strict_types=1);

namespace App\Http\Controllers\Site;

use App\Enums\EnquiryType;
use App\Http\Controllers\Controller;
use App\Http\Requests\Site\SubmitContactMessageRequest;
use App\Services\EnquiryService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\RedirectResponse;

final class ContactMessageController extends Controller
{
    public const SUCCESS_MESSAGE = 'Thank you! Your message has been sent. We will get back to you shortly.';

    public function store(SubmitContactMessageRequest $request, EnquiryService $enquiries): RedirectResponse|JsonResponse
    {
        if (! $request->isSpam()) {
            $enquiries->submit(EnquiryType::Contact, $request->enquiryFields(), null, $request->ip(), $request->userAgent());
        }

        return $request->expectsJson()
            ? response()->json(['message' => self::SUCCESS_MESSAGE], 201)
            : back()->with('contact_status', self::SUCCESS_MESSAGE);
    }
}
