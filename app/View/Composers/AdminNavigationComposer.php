<?php

declare(strict_types=1);

namespace App\View\Composers;

use App\Enums\EnquiryType;
use App\Models\Enquiry;
use Illuminate\View\View;

/**
 * Unread counts shown next to the inbox links in the admin sidebar.
 * One grouped query per page load.
 */
final class AdminNavigationComposer
{
    public function compose(View $view): void
    {
        $unread = Enquiry::query()
            ->unread()
            ->selectRaw('type, COUNT(*) as aggregate')
            ->groupBy('type')
            ->pluck('aggregate', 'type');

        $view->with('unreadCounts', [
            EnquiryType::Product->value => (int) ($unread[EnquiryType::Product->value] ?? 0),
            EnquiryType::Contact->value => (int) ($unread[EnquiryType::Contact->value] ?? 0),
        ]);
    }
}
