export default class Form {
    constructor(data = {}) {
        this.initial = data
        this.errorBag = {}

        for (let key in data) {
            this[key] = data[key]
        }

        this.catch = ({ response }) => {
            this.errors(response.data.errors)
        }
    }

    reset() {
        this.errorBag = {}
        for (let key in this.initial) {
            this[key] = this.initial[key]
        }
    }

    get() {
        let data = {}

        for (let key in this.initial) {
            data[key] = this[key]
        }

        return data
    }

    errors(errors) {
        this.errorBag = errors
    }

    error(field) {
        let errors = optional(this.errorBag)[field]
        return errors ? errors.join('<br />') : null
    }

    filled() {
        for (let field in this.initial) {
            if (!this[field]) return false
        }

        return true
    }
}
