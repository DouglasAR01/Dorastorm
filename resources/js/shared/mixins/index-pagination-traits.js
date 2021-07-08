export default {
    data() {
        return {
            loading: false,
            ep: null,
            params: null,
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
                var query = `${this.ep}?page=${page}`;
                if (this.params){
                    Object.keys(this.params).forEach(key => {
                        if (this.params[key]){
                            query += `&${key}=${this.params[key]}`;
                        }
                    });
                }
                const resp = await axios.get(query);
                this.data = resp.data.data;
                this.meta = resp.data.meta;
                this.links = resp.data.links;
                this.loading = false;
            } catch (error) {
                this.$toasts.error(this.$t("error.fatal"));
            }
        }
    },
}