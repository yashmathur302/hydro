/**
 * Renders the first page of a PDF to a JPEG image in the browser (PDF.js).
 * Loaded on demand, so only the certificate form downloads the library.
 */
import { getDocument, GlobalWorkerOptions } from 'pdfjs-dist';
import PdfWorker from 'pdfjs-dist/build/pdf.worker.min.mjs?worker';

const TARGET_WIDTH = 1200;
const JPEG_QUALITY = 0.9;

let workerPort = null;

/**
 * @param {ArrayBuffer} data PDF bytes
 * @returns {Promise<Blob>} JPEG of page 1, TARGET_WIDTH pixels wide
 */
export async function renderFirstPage(data) {
    workerPort ??= new PdfWorker();
    GlobalWorkerOptions.workerPort = workerPort;

    const loadingTask = getDocument({ data, isEvalSupported: false });

    try {
        const pdf = await loadingTask.promise;
        const page = await pdf.getPage(1);
        const unscaled = page.getViewport({ scale: 1 });
        const viewport = page.getViewport({ scale: TARGET_WIDTH / unscaled.width });

        const canvas = document.createElement('canvas');
        canvas.width = Math.round(viewport.width);
        canvas.height = Math.round(viewport.height);

        const context = canvas.getContext('2d');
        context.fillStyle = '#ffffff';
        context.fillRect(0, 0, canvas.width, canvas.height);

        await page.render({ canvas, canvasContext: context, viewport }).promise;

        return await new Promise((resolve, reject) => {
            canvas.toBlob(
                (blob) => (blob ? resolve(blob) : reject(new Error('Could not create the image.'))),
                'image/jpeg',
                JPEG_QUALITY,
            );
        });
    } finally {
        await loadingTask.destroy();
    }
}
