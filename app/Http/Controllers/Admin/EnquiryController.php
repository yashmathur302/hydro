<?php

declare(strict_types=1);

namespace App\Http\Controllers\Admin;

use App\Enums\EnquiryStatus;
use App\Enums\EnquiryType;
use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\UpdateEnquiryRequest;
use App\Models\Enquiry;
use App\Support\SearchTerm;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Illuminate\View\View;

/**
 * Inbox for both product enquiries and contact messages; the route decides
 * which type is listed.
 */
final class EnquiryController extends Controller
{
    private const PER_PAGE = 20;

    public function index(Request $request, string $type): View
    {
        $enquiryType = EnquiryType::from($type);
        $search = SearchTerm::normalise($request->query('q'));
        $status = EnquiryStatus::tryFrom((string) $request->query('status'));

        $enquiries = Enquiry::query()
            ->ofType($enquiryType)
            ->when($status, static fn (Builder $query, EnquiryStatus $value) => $query->where('status', $value->value))
            ->when($search, static fn (Builder $query, string $term) => $query->where(
                static fn (Builder $inner) => $inner->where('name', 'like', SearchTerm::contains($term))
                    ->orWhere('phone', 'like', SearchTerm::contains($term))
                    ->orWhere('email', 'like', SearchTerm::contains($term))
                    ->orWhere('company', 'like', SearchTerm::contains($term))
                    ->orWhere('product_name', 'like', SearchTerm::contains($term)),
            ))
            ->latest()
            ->latest('id')
            ->paginate(self::PER_PAGE)
            ->withQueryString();

        return view('admin.enquiries.index', [
            'type' => $enquiryType,
            'enquiries' => $enquiries,
            'filters' => ['q' => $search, 'status' => $status],
        ]);
    }

    public function show(Enquiry $enquiry): View
    {
        $enquiry->markAsRead();
        $enquiry->load('product:id,name,slug');

        return view('admin.enquiries.show', ['enquiry' => $enquiry]);
    }

    public function update(UpdateEnquiryRequest $request, Enquiry $enquiry): RedirectResponse
    {
        $enquiry->forceFill([
            'status' => EnquiryStatus::from($request->validated('status')),
            'admin_notes' => $request->validated('admin_notes'),
        ])->save();

        return redirect()->route('admin.enquiries.show', $enquiry)->with('status', 'Enquiry updated.');
    }

    public function destroy(Request $request, Enquiry $enquiry): RedirectResponse
    {
        $type = $enquiry->type;
        $enquiry->delete();

        Log::channel('security')->notice('Enquiry deleted', [
            'enquiry_id' => $enquiry->id,
            'admin_id' => $request->user()?->getAuthIdentifier(),
            'ip' => $request->ip(),
        ]);

        return redirect()->route(self::indexRouteFor($type))->with('status', "{$type->label()} deleted.");
    }

    public static function indexRouteFor(EnquiryType $type): string
    {
        return $type === EnquiryType::Product ? 'admin.enquiries.index' : 'admin.contact-messages.index';
    }
}
