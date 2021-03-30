<template>
  <div>
    <button
      class="btn btn-link"
      v-for="lang in langs"
      :key="lang.lang_info.code"
      @click.prevent="changeLang(lang.lang_info.code)"
    >
      {{ lang.lang_info.title }}
    </button>
  </div>
</template>
<script>
import {setLocale} from "../services/multilang";
export default {
  data() {
    return {
      langs: this.$i18n.messages
    }
  },

  methods: {
    changeLang(langKey) {
      setLocale(this.$i18n, langKey);
      axios.post("/locale", {
        locale: langKey
      });
    }
  }
}
</script>