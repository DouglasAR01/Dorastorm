export default {
    data() {
        return {
            form_initial_state: null
        };
    },
    methods: {
        fieldConfirmed(f1, f2) {
            return f1===f2;
        }
    }
}