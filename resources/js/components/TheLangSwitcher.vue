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
      await setLocale(this.$i18n, langKey);
      this.$router.push({
        path: `/${langKey}`
      });
    }
  }
}
</script>