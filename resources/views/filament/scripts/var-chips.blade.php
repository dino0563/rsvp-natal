
{{-- Alpine logic (guard biar gak didefinisikan dua kali) --}}
<script>
document.addEventListener('alpine:init', () => {
  if (!window.__varChipsDefined) {
    window.__varChipsDefined = true;

    Alpine.data('varChips', ({ targetId, statePath, vars, dummy }) => ({
      targetId, statePath, vars, dummy,
      draft: '',
      el: null,

      init() {
        this.el = document.getElementById(this.targetId);
        if (!this.el) return;
        this.draft = this.el.value || '';

        // Sync ke preview saat user ngetik manual
        this.el.addEventListener('input', () => {
          this.draft = this.el.value;
        });
      },

      insert(token) {
        if (!this.el) return;
        const start = this.el.selectionStart ?? this.el.value.length;
        const end   = this.el.selectionEnd ?? this.el.value.length;
        const text  = `{${token}}`;

        this.el.value = this.el.value.slice(0, start) + text + this.el.value.slice(end);
        const caret = start + text.length;
        this.el.setSelectionRange(caret, caret);
        this.el.focus();

        // Update preview
        this.draft = this.el.value;

        // Paksa Livewire tau: tembak event + set state langsung
        try {
          this.el.dispatchEvent(new InputEvent('input', { bubbles: true }));
          this.el.dispatchEvent(new Event('change', { bubbles: true }));
        } catch (_) {}

        if (window.$wire && this.statePath) {
          $wire.set(this.statePath, this.el.value);
        }
      },

      render() {
        let out = this.draft || '';
        for (const [k, v] of Object.entries(this.dummy)) {
          out = out.replace(new RegExp(`\\{${k}\\}`, 'gi'), v);
        }
        return out;
      },
    }));
  }
});
</script>
