export default {
    // Debug mode. Make sure to set this as false when generating production.
    DEBUG: true,

    /**
     * Features:
     * Enable/disable features of Dorastorm by setting the feature true or false.
     * True means enabled.
     */
    features: {
        AUTH: true,
        USERS_MANAGEMENT: true, //Requires AUTH
        POSTS: true, //Requires AUTH
        QUOTES: true //Requires AUTH
    },

    /**
     * Tag system:
     * Change the key and value params to fit your backend existing tags attributes.
     */
    tags: {
        KEY_NAME: "slug",
        VALUE_NAME: "name"
    }
}