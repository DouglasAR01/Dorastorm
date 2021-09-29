import Vue from 'vue';
import VueI18n from 'vue-i18n';

Vue.use(VueI18n);
let LOADED_LANGS = {};

/*
    Change here your frontend default lang. Keep in mind this language will be always loaded
    when your app start. You should choose the most used language of your users.
    DEFAULT_LANG: The language code (langCode) of the default language. Ex: 'en' / 'es'
    DEF_LANG_IMPORT: Put the default lang import route.

    The DEF_LANG_IMPORT must be the same lang as DEFAULT_LANG. Ex: ../lang/en.json if DEFAULT_LANG = en
*/
const DEFAULT_LANG = "en";
import DEF_LANG_IMPORT from "../lang/en.json";

// Put here every supported locale. The "code" key must be unique.
const SUPPORTED_LOCALES = [
    {
        code: 'en',
        name: 'English',
    },
    {
        code: 'es',
        name: 'Español',
    }
];
LOADED_LANGS[DEFAULT_LANG] = DEF_LANG_IMPORT;

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

export const getSupportedLocales = function () {
    return SUPPORTED_LOCALES;
}
/*
    Returns, if exist, the locale support info based on the langCode. If not exist, returns undefined.
*/
export const getLocale = function (langCode = DEFAULT_LANG) {
    return SUPPORTED_LOCALES.find(lang => lang.code === langCode);
}

/*
    Checks if the locale lang is supported by DoraStorm. If it is, returns the locale's support info object.
    If not, returns the default locale support info.
*/
export const supportedLocalesInclude = function (langCode) {
    const check = getLocale(langCode);
    if (check !== undefined) {
        return check;
    }
    return getLocale();
}
/*
    Check and set the locale to i18n.
*/
export const setLocale = async function (context, langCode) {
    const code = supportedLocalesInclude(langCode).code;
    await loadLocale(code);
    context.locale = code;    
}
/*
    This function gets all DoraStorm supported langs and returns a n-tuple of its lang codes
    ex: (en|es)
*/
export const getLocalesCodes = function () {
    var codes = '';
    SUPPORTED_LOCALES.forEach((lang, index) => {
        codes += lang.code;
        codes += (index !== SUPPORTED_LOCALES.length - 1) ? '|' : '';
    });
    return `(${codes})`;
}
/* 
    Checks if the locale lang is loaded into i18n. Returns true/false.
*/
export const isLocaleLoaded = function (langCode) {
    return Object.keys(LOADED_LANGS).includes(langCode);
}
/* 
    Looks if there is a location code in the Local Storage. If there is, check if it is a valid
    code. In worst case, the default lang is returned
*/
export const loadLocale = async function (localeCode) {
    //const localeCode = localStorage.getItem("lang");
    const locale = supportedLocalesInclude(localeCode);
    if (locale.code != DEFAULT_LANG && !isLocaleLoaded(localeCode)) {
        try {
            const loadedLocale = await import(/* webpackChunkName: "[request]" */ `../lang/${localeCode}.json`);
            LOADED_LANGS[loadedLocale.lang_info.code] = loadedLocale;
            return loadedLocale;
        } catch {
            console.log("Fatal")
        }
    }
}

export default new VueI18n({
    locale: DEFAULT_LANG,
    fallbackLocale: DEFAULT_LANG,
    messages: LOADED_LANGS
});