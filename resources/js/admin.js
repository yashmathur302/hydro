/**
 * Admin panel entry point. Each module enhances markup found through data
 * attributes, so pages only get the behaviour they actually contain.
 */
import { initSidebar } from './admin/sidebar';
import { initPasswordToggles } from './admin/password-toggle';
import { initCharacterCounters } from './admin/character-counter';
import { initImageUploads } from './admin/image-upload';
import { initDismissibleAlerts, focusErrorSummary } from './admin/alerts';

initSidebar();
initPasswordToggles();
initCharacterCounters();
initImageUploads();
initDismissibleAlerts();
focusErrorSummary();
