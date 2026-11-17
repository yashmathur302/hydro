/**
 * Certificate form: the cover image is made automatically from the first
 * page of the PDF and sent in the (hidden) cover_image file input. When an
 * existing certificate has no cover yet, it is made from the saved PDF.
 * If the browser cannot read the PDF, the site shows a PDF icon instead.
 */
const MESSAGES = {
    working: 'Creating the cover from the first page…',
    done: 'Cover created from the first page. It is saved with the certificate.',
    failed: 'The first page could not be read, so a PDF icon will be shown instead.',
};

export function initPdfCovers() {
    document.querySelectorAll('[data-pdf-cover]').forEach((cover) => {
        const form = cover.closest('form');
        const source = form.querySelector('[data-pdf-cover-source]');
        const input = cover.querySelector('[data-pdf-cover-input]');
        const image = cover.querySelector('[data-pdf-cover-image]');
        const placeholder = cover.querySelector('[data-pdf-cover-placeholder]');
        const status = cover.querySelector('[data-pdf-cover-status]');
        const submit = form.querySelector('[type="submit"]');
        let objectUrl = null;
        let run = 0;

        const show = (blob) => {
            if (objectUrl) {
                URL.revokeObjectURL(objectUrl);
            }
            objectUrl = blob ? URL.createObjectURL(blob) : null;
            image.src = objectUrl ?? '';
            image.hidden = !blob;
            placeholder.hidden = Boolean(blob);
        };

        const build = async (loadPdf) => {
            const current = ++run;
            status.textContent = MESSAGES.working;
            submit?.setAttribute('disabled', '');

            try {
                const { renderFirstPage } = await import('./pdf-first-page');
                const blob = await renderFirstPage(await loadPdf());

                if (current !== run) {
                    return;
                }

                const transfer = new DataTransfer();
                transfer.items.add(new File([blob], 'certificate-cover.jpg', { type: 'image/jpeg' }));
                input.files = transfer.files;
                show(blob);
                status.textContent = MESSAGES.done;
            } catch (error) {
                console.warn('Certificate cover:', error);
                if (current !== run) {
                    return;
                }
                input.value = '';
                show(null);
                status.textContent = MESSAGES.failed;
            } finally {
                if (current === run) {
                    submit?.removeAttribute('disabled');
                }
            }
        };

        source.addEventListener('change', () => {
            const [file] = source.files;

            if (file && file.type === 'application/pdf') {
                build(() => file.arrayBuffer());
            }
        });

        if (cover.dataset.pdfUrl) {
            build(async () => {
                const response = await fetch(cover.dataset.pdfUrl, { credentials: 'same-origin' });
                if (!response.ok) {
                    throw new Error('PDF not found.');
                }
                return response.arrayBuffer();
            });
        }
    });
}
