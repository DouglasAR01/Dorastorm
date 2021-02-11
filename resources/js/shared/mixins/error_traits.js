export default {
    data() {
        return {
            ferror: null,
            errors: null,
        }
    },
    methods: {
        errorFor(field) {
            return null !== this.errors && this.errors[field]
                ? this.errors[field]
                : null;
        }
    }
}