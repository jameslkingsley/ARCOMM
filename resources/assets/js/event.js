window.Events = new class {
    constructor() {
        this.vue = new Vue();
    }

    fire(event, data = null) {
        this.vue.$emit(event.toLowerCase(), data);
    }

    listen(event, callback) {
        this.vue.$on(event.toLowerCase(), callback);
    }

    listenOnce(event, callback) {
        this.vue.$once(event.toLowerCase(), callback);
    }
}
