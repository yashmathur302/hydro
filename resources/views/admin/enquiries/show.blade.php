@php
    use App\Enums\EnquiryStatus;
    use App\Http\Controllers\Admin\EnquiryController;

    $statusOptions = collect(EnquiryStatus::cases())->mapWithKeys(fn (EnquiryStatus $status) => [$status->value => $status->label()]);
    $whatsappNumber = preg_replace('/\D/', '', $enquiry->phone);
@endphp

<x-admin.layout :title="$enquiry->type->label()">
    <x-slot:header>
        <x-admin.page-header :title="$enquiry->name" :description="$enquiry->type->label().' · received '.$enquiry->created_at->format('d M Y, h:i A')">
            <x-admin.button :href="route(EnquiryController::indexRouteFor($enquiry->type))" variant="secondary">Back to {{ mb_strtolower($enquiry->type->pluralLabel()) }}</x-admin.button>
        </x-admin.page-header>
    </x-slot:header>

    <div class="editor-layout">
        <div class="editor-layout__main">
            <x-admin.card title="Message">
                <dl class="row-list">
                    @if ($enquiry->product_name)
                        <div class="row-list__item">
                            <dt>Product</dt>
                            <dd>
                                @if ($enquiry->product)
                                    <a href="{{ route('admin.products.edit', $enquiry->product) }}">{{ $enquiry->product_name }}</a>
                                @else
                                    {{ $enquiry->product_name }} <span class="text-secondary">(deleted)</span>
                                @endif
                            </dd>
                        </div>
                    @endif
                    @foreach (['subject' => 'Subject', 'company' => 'Company', 'city' => 'City'] as $field => $label)
                        @if ($enquiry->{$field})
                            <div class="row-list__item">
                                <dt>{{ $label }}</dt>
                                <dd>{{ $enquiry->{$field} }}</dd>
                            </div>
                        @endif
                    @endforeach
                </dl>

                @if ($enquiry->message)
                    <div class="message-body">{{ $enquiry->message }}</div>
                @else
                    <p class="text-secondary mb-0">No message was written.</p>
                @endif
            </x-admin.card>

            <form method="POST" action="{{ route('admin.enquiries.update', $enquiry) }}" novalidate>
                @csrf
                @method('PUT')

                <x-admin.card title="Follow-up" description="Only you can see the status and notes.">
                    <x-admin.error-summary />
                    <div class="form-stack">
                        <x-admin.form.select name="status" label="Status" :options="$statusOptions" :value="$enquiry->status->value" required />
                        <x-admin.form.textarea name="admin_notes" label="Notes" :value="$enquiry->admin_notes" maxlength="5000" rows="4" hint="e.g. called on Monday, sent quotation." />
                    </div>

                    <x-slot:footer>
                        <x-admin.button type="submit" icon="check">Save follow-up</x-admin.button>
                    </x-slot:footer>
                </x-admin.card>
            </form>
        </div>

        <aside class="editor-layout__side">
            <x-admin.card title="Contact">
                <dl class="row-list">
                    <div class="row-list__item"><dt>Phone</dt><dd>{{ $enquiry->phone }}</dd></div>
                    @if ($enquiry->email)
                        <div class="row-list__item"><dt>Email</dt><dd>{{ $enquiry->email }}</dd></div>
                    @endif
                    <div class="row-list__item"><dt>Status</dt><dd><x-admin.badge :variant="$enquiry->status->badgeVariant()">{{ $enquiry->status->label() }}</x-admin.badge></dd></div>
                </dl>

                <x-slot:footer>
                    <div class="button-row">
                        <x-admin.button :href="'tel:'.preg_replace('/[^0-9+]/', '', $enquiry->phone)" variant="secondary" size="small" icon="phone">Call</x-admin.button>
                        @if (strlen($whatsappNumber) >= 10)
                            <x-admin.button :href="'https://wa.me/'.$whatsappNumber" variant="secondary" size="small" icon="whatsapp" target="_blank" rel="noopener">WhatsApp</x-admin.button>
                        @endif
                        @if ($enquiry->email)
                            <x-admin.button :href="'mailto:'.$enquiry->email" variant="secondary" size="small" icon="mail">Email</x-admin.button>
                        @endif
                    </div>
                </x-slot:footer>
            </x-admin.card>

            <x-admin.card title="Delete">
                <p class="text-secondary mb-0">Permanently removes this {{ mb_strtolower($enquiry->type->label()) }}.</p>
                <x-slot:footer>
                    <x-admin.delete-button :action="route('admin.enquiries.destroy', $enquiry)" confirm="Delete this message from {{ $enquiry->name }}? This cannot be undone." />
                </x-slot:footer>
            </x-admin.card>
        </aside>
    </div>
</x-admin.layout>
