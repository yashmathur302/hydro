/**
 * Toolbar buttons that wrap the selected text in Markdown syntax.
 */
const ACTIONS = {
    bold: (text) => ({ before: '**', after: '**', placeholder: text || 'bold text' }),
    italic: (text) => ({ before: '*', after: '*', placeholder: text || 'italic text' }),
    heading: (text) => ({ before: '\n## ', after: '\n', placeholder: text || 'Heading' }),
    list: (text) => ({
        before: '\n',
        after: '\n',
        placeholder: (text || 'List item').split('\n').map((line) => `- ${line}`).join('\n'),
    }),
    link: (text) => ({ before: '[', after: '](https://)', placeholder: text || 'link text' }),
};

function applyAction(textarea, action) {
    const { selectionStart: start, selectionEnd: end, value } = textarea;
    const { before, after, placeholder } = ACTIONS[action](value.slice(start, end));

    textarea.focus();
    textarea.setRangeText(`${before}${placeholder}${after}`, start, end, 'end');

    const selectFrom = start + before.length;
    textarea.setSelectionRange(selectFrom, selectFrom + placeholder.length);
    textarea.dispatchEvent(new Event('input', { bubbles: true }));
}

export function initMarkdownToolbars() {
    document.querySelectorAll('[data-markdown-editor]').forEach((editor) => {
        const textarea = editor.querySelector('[data-md-input]');

        editor.querySelectorAll('[data-md-action]').forEach((button) => {
            button.addEventListener('click', () => applyAction(textarea, button.dataset.mdAction));
        });
    });
}
