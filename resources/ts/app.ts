import modal from './wireui/modal'
import { Alpine } from './wireui/alpine'

declare global {
    interface Window {
      Alpine: Alpine
    }
  }

document.addEventListener('alpine:init', () => {
    window.Alpine.data('wireui_modal', modal);
});
