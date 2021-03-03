export default {
    data() {
        return {
            form_initial_state: null,
            confirmation_field: null
        };
    },
    methods: {
        fieldConfirmed(f1, f2) {
            return f1===f2;
        }
    }
}