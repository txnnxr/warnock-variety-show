// Full-screen photo viewer. Takes [{url, caption, alt}] and opens on click;
// arrows, arrow keys and swipes move between photos, Escape closes.
export default (photos) => ({
    photos,
    index: null,
    touchStartX: null,
    returnFocus: null,

    get isOpen() {
        return this.index !== null;
    },

    get photo() {
        return this.photos[this.index] ?? {};
    },

    open(index) {
        this.returnFocus = document.activeElement;
        this.index = index;
        document.body.style.overflow = 'hidden';
        this.$nextTick(() => this.$refs.close?.focus());
        this.preloadNeighbors();
    },

    close() {
        this.index = null;
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
});
