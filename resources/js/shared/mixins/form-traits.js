export default {
    data() {
        return {
            formInitialState: null
        };
    },
    methods: {
        fieldConfirmed(f1, f2) {
            return f1===f2;
        }
    }
}