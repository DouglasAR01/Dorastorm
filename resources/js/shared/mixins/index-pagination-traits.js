export default {
    data() {
        return {
            loading: false,
            ep: null,
            data: null,
            meta: null,
            links: null
        };
    },
    beforeMount() {
        this.navigate(1);
    },
    methods: {
        async navigate(page) {
            this.loading = true;
            try {
                const resp = await axios.get(this.ep + page);
                this.data = resp.data.data;
                this.meta = resp.data.meta;
                this.links = resp.data.links;
                this.loading = false;
            } catch (error) {
                this.$toasts.error(this.$t("error.fatal"));
            }
        },
    },
}