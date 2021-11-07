import CONFIG from "../../app.config";
/**
 * This mixin is planned to be paired with the TagsInput component.
 */
export default {
    data() {
        return {
            cKey: CONFIG.tags.KEY_NAME,
            cValue: CONFIG.tags.VALUE_NAME,
            existingTags: [],
            selectedTags: []
        }
    },
    methods: {
        tagsToString() {
            var tags = "";
            this.selectedTags.forEach(tagObj => {
                tags += tagObj[this.cValue] + ",";
            });
            return tags;
        },
        arrayToTagsArray(ogArray) {
            var newArray = [];
            var n = 0;
            ogArray.forEach(item => {
                var newTag = {};
                newTag[this.cKey] = n;
                newTag[this.cValue] = item;
                newArray.push(newTag);
                n += 1;
            })
            return newArray;
        }
    }
}