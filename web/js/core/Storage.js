export default class Storage {
    #key;

    constructor(key) {
        this.#key = key;
    }

    get local() {
        const storages = localStorage.getItem(this.#key);
        if (storages !== null && storages.length) {
            return JSON.parse(storages).sort((a, b) => a.order - b.order);
        }

        return [];
    }

    set local(data) {
        localStorage.setItem(this.#key, JSON.stringify(data));
    }
}