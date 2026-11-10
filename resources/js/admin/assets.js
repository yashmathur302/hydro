/**
 * Registers the admin images with Vite so Blade can reference them with
 * Vite::asset() (fingerprinted, cached and served from /build).
 */
import.meta.glob('../../images/admin/*.webp', { eager: true, query: '?url', import: 'default' });
