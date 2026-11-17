/**
 * Previews an image as soon as it is chosen, before the form is saved.
 * Object URLs are revoked to avoid leaking memory on repeated selections.
 */
export function initImageUploads() {
    document.querySelectorAll('[data-image-upload]').forEach((upload) => {
        const input = upload.querySelector('[data-preview-input]');
        const image = upload.querySelector('[data-preview-image]');
        const frame = upload.querySelector('[data-preview-frame]');
        const placeholder = upload.querySelector('[data-preview-placeholder]');
        const filename = upload.querySelector('[data-preview-filename]');
        let objectUrl = null;

        input.addEventListener('change', () => {
            const [file] = input.files;

            if (objectUrl) {
                URL.revokeObjectURL(objectUrl);
                objectUrl = null;
            }

            filename.textContent = file ? file.name : '';

            if (!file || !file.type.startsWith('image/')) {
                return;
            }

            objectUrl = URL.createObjectURL(file);
            image.src = objectUrl;
            image.hidden = false;
            placeholder.hidden = true;
            frame.removeAttribute('data-empty');
        });
    });
}
