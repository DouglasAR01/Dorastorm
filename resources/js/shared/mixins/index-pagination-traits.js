export default {
    props: {
        aditionalParams: {
            required: false,
            type: Object,
            default: () => (null)
        },
    },
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
        this._initializeComponent();
    },
    methods: {        
        _initializeParams() {
            this.params = {
                q: null,
            };
            if (this.aditionalParams) {
                Object.keys(this.aditionalParams).forEach(
                    (param) => (this.params[param] = this.aditionalParams[param])
                );
            }
        },
        _initializeComponent() {
            this._initializeParams();
            this.navigate(1);
        },
        async navigate(page) {
            this.loading = true;
            try {
                var query = `${this.ep}?page=${page}`;
                if (this.params) {
                    Object.keys(this.params).forEach(key => {
                        if (this.params[key]) {
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
    watch: {
        aditionalParams: function () {
            this._initializeComponent();
        }
    }
}