import Vue from 'vue';
import VueI18n from 'vue-i18n';

//lang
import en from "../../lang/en/en.json";
import es from "../../lang/es/es.json";

//General idea taken from: https://phrase.com/blog/posts/ultimate-guide-to-vue-localization-with-vue-i18n

Vue.use(VueI18n);

const DEFAULT_FALLBACK_LOCALE = "en";
const LANGS = {
    en: en,
    es: es
}

export const getBrowserLocale = function (options = {}) {
    const defaultOptions = { countryCodeOnly: false }

    const opt = { ...defaultOptions, ...options }

    const navigatorLocale =
        navigator.languages !== undefined
            ? navigator.languages[0]
            : navigator.language

    if (!navigatorLocale) {
        return undefined
    }

    const trimmedLocale = opt.countryCodeOnly
        ? navigatorLocale.trim().split(/-|_/)[0]
        : navigatorLocale.trim()

    return trimmedLocale
}

export const supportedLocalesInclude = function (langKey) {
    if (langKey in LANGS) {
        return LANGS[langKey].lang_info.code;
    }
    return DEFAULT_FALLBACK_LOCALE;
}

export const getStartingLocale = function () {
    const savedLocale = localStorage.getItem("lang");
    if (!!savedLocale) {
        return supportedLocalesInclude(savedLocale);
    }
    const browserLocale = getBrowserLocale({ countryCodeOnly: true });
    return supportedLocalesInclude(browserLocale);
}

export const setLocale = function (context, langKey, persistent = true) {
    const newLocale = supportedLocalesInclude(langKey);
    if (persistent) {
        localStorage.setItem("lang", newLocale);
    }
    context.locale = newLocale;
}

export default new VueI18n({
    locale: getStartingLocale(),
    fallbackLocale: DEFAULT_FALLBACK_LOCALE,
    messages: LANGS
});