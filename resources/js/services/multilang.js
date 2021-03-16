import Store from "./store";
import Vue from 'vue';
import VueI18n from 'vue-i18n';

//lang
import en from "../../lang/en/en.json";

Vue.use(VueI18n);

const messages = {
    en: en
}

export default new VueI18n({
    locale: "en",
    fallbackLocale: "en",
    messages: messages
});