<template>
  <div class="container rounded bg-white p-3">
		<div v-if="!loading">
			<user-data-card v-bind="user"></user-data-card>
		</div>
		<div v-else>
			<p class="lead">{{ $t("message.loading") }}</p>
		</div>
	</div>
</template>
<script>
import { is404 } from "../../shared/utils/responses";
import UserDataCard from "./UserDataCard";
export default {
	components: {
		UserDataCard
	},
  data() {
    return {
      loading: false,
      user: null,
    };
  },
  async created() {
    this.loading = true;
    if (this.$route.query && this.$route.query.user_id) {
      try {
        this.user = (
          await axios.get("/api/users/" + this.$route.query.user_id)
        ).data.data;
        this.loading = false;
				return;
      } catch (error) {
        if (is404(error)) {
          this.$toasts.error($t("error.404.specific.user"));
          this.$router.push({
            name: "404",
						params: { '0': '/'}
          });
					return;
        }
      }
    }
    this.$toasts.error($t("error.fatal"));
  },
};
</script>