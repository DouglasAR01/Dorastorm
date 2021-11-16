export default {
    // Debug mode. Make sure to set this as false when generating production.
    DEBUG: true,

    /**
     * Format used by dayjs to translate timestamps.
     */
    DATE_FORMAT: "YYYY/MM/DD",

    /**
     * App meta configuration. Turn on TEMPLATE_ON if you want to use TEMPLATE as sufix
     * or prefix on every page.
     * Set APP_TITLE as the lang key pointing to your app title.
     */
    meta: {        
        title: {
            APP_TITLE: "modules.app_title",
            TEMPLATE_ON: true,
            TEMPLATE: "%s | Dorastorm"
        }
    },

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