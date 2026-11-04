<?php

declare(strict_types=1);

namespace App\Enums;

/**
 * Every setting the admin panel can manage. Using an enum keeps the allowed
 * keys in one place, so arbitrary keys can never be written to the database.
 */
enum SiteSettingKey: string
{
    // Branding
    case SiteName = 'site_name';
    case SiteTagline = 'site_tagline';
    case LogoPath = 'logo_path';
    case FaviconPath = 'favicon_path';

    // Contact
    case ContactPhone = 'contact_phone';
    case ContactEmail = 'contact_email';
    case ContactAddress = 'contact_address';
    case WhatsappNumber = 'whatsapp_number';
    case EnquiryNotificationEmail = 'enquiry_notification_email';

    // Social profiles
    case FacebookUrl = 'facebook_url';
    case InstagramUrl = 'instagram_url';
    case LinkedinUrl = 'linkedin_url';
    case YoutubeUrl = 'youtube_url';
    case XUrl = 'x_url';

    // Default SEO (used when a page has no SEO fields of its own)
    case DefaultMetaTitle = 'default_meta_title';
    case DefaultMetaDescription = 'default_meta_description';
    case DefaultMetaKeywords = 'default_meta_keywords';

    // Tracking
    case Ga4MeasurementId = 'ga4_measurement_id';
    case FacebookPixelId = 'facebook_pixel_id';
    case GoogleSiteVerification = 'google_site_verification';

    /**
     * Keys whose value is an uploaded file path rather than plain text.
     */
    public function isFile(): bool
    {
        return in_array($this, [self::LogoPath, self::FaviconPath], true);
    }

    /**
     * Keys that are edited as plain text fields on the settings form.
     *
     * @return list<self>
     */
    public static function textKeys(): array
    {
        return array_values(array_filter(
            self::cases(),
            static fn (self $key): bool => ! $key->isFile(),
        ));
    }
}
