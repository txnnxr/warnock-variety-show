// A show's photos: the gallery grid, its full-screen viewer, and the admin
// upload form. The photo list lives in a store so uploads and deletes show
// up in the grid without reloading the page.
export default (Alpine) => {
    Alpine.store('photos', { items: [] });

    // Photos are [{id, url, caption, alt, delete_url}]. Arrows, arrow keys
    // and swipes move through the viewer; Escape closes it.
    Alpine.data('photoGallery', (initial) => ({
        index: 0,
        shown: false,
        touchStartX: null,
        returnFocus: null,
        error: '',

        init() {
            this.$store.photos.items = initial;
        },

        get photos() {
            return this.$store.photos.items;
        },

        get isOpen() {
            return this.shown;
        },

        get photo() {
            return this.photos[this.index] ?? {};
        },

        open(index) {
            this.returnFocus = document.activeElement;
            this.index = index;
            this.shown = true;
            document.body.style.overflow = 'hidden';
            this.$nextTick(() => this.$refs.close?.focus());
            this.preloadNeighbors();
        },

        close() {
            // Keep `index` so the photo stays put while the viewer fades out.
            this.shown = false;
            document.body.style.overflow = '';
            this.returnFocus?.focus();
        },

        step(by) {
            if (!this.isOpen || this.photos.length < 2) return;
            this.index = (this.index + by + this.photos.length) % this.photos.length;
            this.preloadNeighbors();
        },

        preloadNeighbors() {
            [1, -1].forEach((by) => {
                const neighbor = this.photos[(this.index + by + this.photos.length) % this.photos.length];
                if (neighbor) new Image().src = neighbor.url;
            });
        },

        onKey(event) {
            if (!this.isOpen) return;
            if (event.key === 'Escape') this.close();
            if (event.key === 'ArrowRight') this.step(1);
            if (event.key === 'ArrowLeft') this.step(-1);
        },

        touchStart(event) {
            this.touchStartX = event.changedTouches[0].clientX;
        },

        touchEnd(event) {
            if (this.touchStartX === null) return;
            const distance = event.changedTouches[0].clientX - this.touchStartX;
            this.touchStartX = null;
            if (Math.abs(distance) > 50) this.step(distance < 0 ? 1 : -1);
        },

        async remove(photo) {
            this.error = '';
            photo.deleting = true;

            try {
                await window.axios.delete(photo.delete_url);
                this.photos.splice(this.photos.indexOf(photo), 1);
            } catch {
                photo.deleting = false;
                this.error = "That photo couldn't be deleted. Try again.";
            }
        },
    }));

    Alpine.data('photoUpload', (url) => ({
        progress: null,
        message: '',
        error: '',

        get uploading() {
            return this.progress !== null;
        },

        async submit(form) {
            // Read the form before `uploading` disables its inputs, since
            // disabled inputs are left out of FormData.
            const body = new FormData(form);
            this.message = '';
            this.error = '';
            this.progress = 0;

            try {
                const { data } = await window.axios.post(url, body, {
                    onUploadProgress: (event) => {
                        if (event.total) this.progress = Math.round((event.loaded / event.total) * 100);
                    },
                });

                this.$store.photos.items.push(...data.photos);
                this.message = data.photos.length === 1 ? 'Uploaded 1 photo.' : `Uploaded ${data.photos.length} photos.`;
                form.reset();
            } catch (exception) {
                this.error = this.describe(exception.response);
            } finally {
                this.progress = null;
            }
        },

        describe(response) {
            if (response?.status === 422) return Object.values(response.data.errors)[0][0];
            if (response?.status === 413) return 'Those photos are too big to send at once. Try fewer at a time.';
            return 'The upload failed. Check your connection and try again.';
        },
    }));
};
