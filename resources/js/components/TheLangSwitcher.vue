<template>
  <div>
    <button
      class="btn btn-link"
      v-for="lang in langs"
      :key="lang.code"
      @click.prevent="changeLang(lang.code)"
    >
      {{ lang.name }}
    </button>
  </div>
</template>
<script>
import {setLocale, getSupportedLocales} from "../services/multilang";
export default {
  data() {
    return {
      langs: getSupportedLocales()
    }
  },

  methods: {
    async changeLang(langKey) {      
      axios.post("/locale", {
        locale: langKey
      });
      const locale = await setLocale(this.$i18n, langKey);
      this.$store.dispatch('setLocale', locale);
      this.$router.replace({
        name: this.$route.name,
        params: { locale: langKey}
      });
    }
  }
}
</script>