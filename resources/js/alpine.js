document.addEventListener('alpine:init', () => {
    Alpine.data('loadFile', () => ({
        isUploading: false,
        progress: 0,

        loading: {
            ['x-on:livewire-upload-start']() {
                this.isUploading = true
            },

            ['x-on:livewire-upload-finish']() {
                this.isUploading = false; this.progress= 0
            },

            ['x-on:livewire-upload-error']() {
                this.isUploading = false; this.progress= 0
            },

            ['x-on:livewire-upload-progress']() {
                this.progress = this.$event.detail.progress
            },
        },
    }));
});
