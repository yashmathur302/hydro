{{--
    Contact Us page: banner, three contact cards (from Site settings), text
    and photo beside the contact form, and the Google Maps embed.
--}}
@inject('siteSettings', 'App\Services\SiteSettingService')

@php
    use App\Enums\SiteSettingKey as Key;
    use App\Http\Requests\Site\SubmitProductEnquiryRequest;

    $address = $siteSettings->get(Key::ContactAddress);
    $phones = array_values(array_filter([$siteSettings->get(Key::ContactPhone), $cards['extra_phone']]));
    $emails = array_values(array_filter([$siteSettings->get(Key::ContactEmail), $cards['extra_email']]));
    $telHref = static fn (string $phone): string => 'tel:'.preg_replace('/[^0-9+]/', '', $phone);
@endphp

<x-site.layout :seo="$seo">
    <x-site.page-banner :title="$banner['title']" :eyebrow="$banner['eyebrow']" :image-url="$banner['background_image_url']" />

    @if ($address || $phones !== [] || $emails !== [])
        <section class="contact-cards" aria-label="Contact details">
            <div class="container">
                <ul class="contact-cards__grid">
                    @if ($address)
                        <li class="contact-card">
                            <span class="contact-card__icon" aria-hidden="true"><x-site.icon name="map-pin" /></span>
                            <h2 class="contact-card__title">{{ $cards['address_title'] }}</h2>
                            <p class="contact-card__text">{{ $address }}</p>
                        </li>
                    @endif
                    @if ($phones !== [])
                        <li class="contact-card contact-card--featured">
                            <span class="contact-card__icon" aria-hidden="true"><x-site.icon name="phone-ring" /></span>
                            <h2 class="contact-card__title">{{ $cards['phone_title'] }}</h2>
                            <p class="contact-card__text">
                                @foreach ($phones as $phone)
                                    <a href="{{ $telHref($phone) }}">{{ $phone }}</a>
                                @endforeach
                            </p>
                        </li>
                    @endif
                    @if ($emails !== [])
                        <li class="contact-card">
                            <span class="contact-card__icon" aria-hidden="true"><x-site.icon name="mail" /></span>
                            <h2 class="contact-card__title">{{ $cards['email_title'] }}</h2>
                            <p class="contact-card__text">
                                @foreach ($emails as $email)
                                    <a href="mailto:{{ $email }}">{{ $email }}</a>
                                @endforeach
                            </p>
                        </li>
                    @endif
                </ul>
            </div>
        </section>
    @endif

    <section class="contact-form-section section" aria-labelledby="contact-intro-heading">
        <div class="container contact-form-section__grid">
            <div class="contact-intro">
                <h2 class="section-title contact-intro__title" id="contact-intro-heading">{{ $form['heading'] }}</h2>
                @if ($form['text'])
                    <p class="contact-intro__text">{{ $form['text'] }}</p>
                @endif
                @if ($form['image_url'])
                    <img class="contact-intro__image" src="{{ $form['image_url'] }}" alt="" loading="lazy" decoding="async">
                @endif
            </div>

            <div class="contact-form-wrap" id="contact-form">
                <h2 class="section-title contact-form-wrap__title">{{ $form['form_heading'] }}</h2>
                @if ($form['form_note'])
                    <p class="contact-form-wrap__note">{{ $form['form_note'] }}</p>
                @endif

                @if (session('contact_status'))
                    <p class="form-alert form-alert--success" role="status">{{ session('contact_status') }}</p>
                @elseif ($errors->any())
                    <p class="form-alert form-alert--error" role="alert">Please check the highlighted fields.</p>
                @endif

                <form class="contact-form" method="POST" action="{{ route('contact.store') }}" novalidate>
                    @csrf

                    {{-- Honeypot: hidden from people, filled in by spam bots. --}}
                    <div class="contact-form__trap" aria-hidden="true">
                        <label for="contact-website">Website</label>
                        <input type="text" id="contact-website" name="{{ SubmitProductEnquiryRequest::HONEYPOT_FIELD }}" tabindex="-1" autocomplete="off">
                    </div>

                    @foreach ([
                        ['name' => 'name', 'label' => 'Your Name', 'icon' => 'user', 'type' => 'text', 'required' => true, 'autocomplete' => 'name', 'max' => 100],
                        ['name' => 'phone', 'label' => 'Phone Number', 'icon' => 'phone', 'type' => 'tel', 'required' => true, 'autocomplete' => 'tel', 'max' => 30],
                        ['name' => 'email', 'label' => 'Email Address', 'icon' => 'mail', 'type' => 'email', 'required' => false, 'autocomplete' => 'email', 'max' => 191],
                    ] as $field)
                        <div @class(['contact-field', 'has-error' => $errors->has($field['name'])])>
                            <label class="visually-hidden" for="contact-{{ $field['name'] }}">{{ $field['label'] }}{{ $field['required'] ? ' (required)' : '' }}</label>
                            <x-site.icon :name="$field['icon']" class="contact-field__icon" />
                            <input class="contact-field__input" type="{{ $field['type'] }}" id="contact-{{ $field['name'] }}" name="{{ $field['name'] }}" value="{{ old($field['name']) }}" placeholder="{{ $field['label'] }}{{ $field['required'] ? '*' : '' }}" maxlength="{{ $field['max'] }}" autocomplete="{{ $field['autocomplete'] }}" @required($field['required']) @error($field['name']) aria-invalid="true" aria-describedby="contact-{{ $field['name'] }}-error" @enderror>
                            @error($field['name'])<p class="contact-field__error" id="contact-{{ $field['name'] }}-error">{{ $message }}</p>@enderror
                        </div>
                    @endforeach

                    <div @class(['contact-field', 'contact-field--message', 'has-error' => $errors->has('message')])>
                        <label class="visually-hidden" for="contact-message">Your message (required)</label>
                        <x-site.icon name="pencil" class="contact-field__icon" />
                        <textarea class="contact-field__input" id="contact-message" name="message" rows="4" placeholder="Enter Your Message Here*" maxlength="3000" required @error('message') aria-invalid="true" aria-describedby="contact-message-error" @enderror>{{ old('message') }}</textarea>
                        @error('message')<p class="contact-field__error" id="contact-message-error">{{ $message }}</p>@enderror
                    </div>

                    <button type="submit" class="btn btn--accent contact-form__submit">
                        <x-site.icon name="send" />
                        {{ $form['button_label'] }}
                    </button>
                </form>
            </div>
        </div>
    </section>

    @if ($map['embed_url'])
        <section class="contact-map" aria-label="Location map">
            <iframe class="contact-map__frame" src="{{ $map['embed_url'] }}" title="Our location on Google Maps" loading="lazy" referrerpolicy="no-referrer-when-downgrade" allowfullscreen></iframe>
        </section>
    @endif
</x-site.layout>
