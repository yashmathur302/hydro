/**
 * Multi-image picker with drag-and-drop and previews of the chosen files.
 */
function renderPreviews(input, list, urls) {
    urls.forEach((url) => URL.revokeObjectURL(url));
    urls.length = 0;
    list.replaceChildren();

    [...input.files].forEach((file) => {
        const item = document.createElement('li');
        const caption = document.createElement('span');

        item.className = 'dropzone__preview';
        caption.textContent = file.name;

        if (file.type.startsWith('image/')) {
            const image = document.createElement('img');
            const url = URL.createObjectURL(file);

            urls.push(url);
            image.src = url;
            image.alt = '';
            item.append(image);
        }

        item.append(caption);
        list.append(item);
    });
}

export function initMultiUploads() {
    document.querySelectorAll('[data-multi-upload]').forEach((zone) => {
        const input = zone.querySelector('[data-multi-upload-input]');
        const list = zone.querySelector('[data-multi-upload-previews]');
        const urls = [];

        input.addEventListener('change', () => renderPreviews(input, list, urls));

        ['dragenter', 'dragover'].forEach((type) => zone.addEventListener(type, (event) => {
            event.preventDefault();
            zone.classList.add('is-dragging');
        }));

        ['dragleave', 'drop'].forEach((type) => zone.addEventListener(type, () => zone.classList.remove('is-dragging')));

        zone.addEventListener('drop', (event) => {
            event.preventDefault();

            if (event.dataTransfer?.files.length) {
                input.files = event.dataTransfer.files;
                renderPreviews(input, list, urls);
            }
        });
    });
}
