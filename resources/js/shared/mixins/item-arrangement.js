export default {
    data() {
        return {
            columns: 4,
            items: null
        };
    },
    
    computed: {
        rows() {
            return this.items == null
                ? 0
                : Math.ceil(this.items.length / this.columns);
        },
    },

    methods: {
        elementsInRow(row) {
            return this.items.slice((row - 1) * this.columns, row * this.columns);
        },

        fillerElements(elementsInside) {
            return this.columns - elementsInside.length;
        },
    },

}