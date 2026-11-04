/**
 * Admin panel entry point. Each module enhances markup found through data
 * attributes, so pages only get the behaviour they actually contain.
 */
import { initSidebar } from './admin/sidebar';
import { initSectionNav } from './admin/section-nav';
import { initPasswordToggles } from './admin/password-toggle';
import { initCharacterCounters } from './admin/character-counter';
import { initImageUploads } from './admin/image-upload';
import { initMultiUploads } from './admin/multi-upload';
import { initMarkdownToolbars } from './admin/markdown-toolbar';
import { initRepeaters } from './admin/repeater';
import { initConfirmDialog } from './admin/confirm-dialog';
import { initDismissibleAlerts, focusFirstInvalidField } from './admin/alerts';

initSidebar();
initSectionNav();
initPasswordToggles();
initCharacterCounters();
initImageUploads();
initMultiUploads();
initMarkdownToolbars();
initRepeaters();
initConfirmDialog();
initDismissibleAlerts();
focusFirstInvalidField();
