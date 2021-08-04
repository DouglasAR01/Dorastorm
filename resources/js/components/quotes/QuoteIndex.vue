<template>
  <div class="container wp bg-light">
    <div class="text-center">
      <h3>{{ $t("modules.quotes.list") }}</h3>
    </div>
    <div v-if="loading" key="load">{{ $t("message.loading") }}</div>
    <div v-else key="load">
      <div class="table-responsive">
        <table class="table table-hover">
          <thead>
            <tr>
              <th scope="col">{{ $t("message.id") }}</th>
              <th scope="col">{{ $t("modules.quotes.subject") }}</th>
              <th scope="col">{{ $t("modules.quotes.name") }}</th>
              <th scope="col">{{ $t("message.created_at")}}</th>
              <th scope="col">{{ $t("message.action") }}</th>
            </tr>
          </thead>
          <transition-group tag="tbody" name="list">
            <template v-for="quote in data">
              <tr :key="quote.id" class="list-item">
                <td>
                  {{ quote.id }}
                </td>
                <td>
                  {{ quote.subject }}
                </td>
                <td>
                  {{ quote.name }}
                </td>
                <td>
                  {{ quote.created_at | humanDate}}
                </td>
                <td>
                  <button
                    class="btn btn-link"
                    @click.prevent="toggle(quote.id)"
                  >
                    <i class="far fa-eye"></i>
                  </button>
                  <button
                    class="btn btn-link"
                    @click.prevent="deleteQuote(quote.id)"
                    :disabled="deleting"
                  >
                    <i class="fas fa-trash-alt"></i>
                  </button>
                </td>
              </tr>
              <tr :key="'co' + quote.id" v-if="opened.includes(quote.id)" class="list-item no-border">
                <td colspan="5" class="p-0">
                  <quote-table-item-data :quote="quote" />
                </td>
              </tr>
            </template>
          </transition-group>
        </table>
      </div>
      <simple-pagination
        :meta="meta"
        :links="links"
        @navigating="navigate"
      ></simple-pagination>
      <confirm-dialogue-modal ref="confirmDialogue"></confirm-dialogue-modal>
    </div>
  </div>
</template>
<script>
import QuoteTableItemData from "./QuoteTableItemData";
import ConfirmDialogueModal from "../../shared/components/modals/ConfirmDialogueModal";
import { is404 } from "../../shared/utils/responses";
import Permissions from "../../services/role-permissions";
import SimplePagination from "../../shared/components/SimplePagination";
import IndexPaginationTraits from "../../shared/mixins/index-pagination-traits";
export default {
  components: {
    ConfirmDialogueModal,
    SimplePagination,
    QuoteTableItemData 
  },
  mixins: [IndexPaginationTraits],
  data() {
    return {
      opened: [],
      deleting: false,
    };
  },
  created() {
    this.ep = "/api/quotes";
    this.params = {
      q: null,
    };
  },
  computed: {
    userCanDelete() {
      return Permissions.checkUserPermission(
        this.$store.state.user,
        Permissions.core.DELETE_QUOTES
      );
    },
  },
  methods: {
    toggle(id) {
      const index = this.opened.indexOf(id);
      if (index > -1) {
        this.opened.splice(index, 1);
      } else {
        this.opened.push(id);
      }
    },
    async deleteQuote(id) {
      const ok = await this.$refs.confirmDialogue.show({
        title: this.$t("modules.quotes.delete"),
        message: this.$t("modules.quotes.delete_warning"),
        okButton: this.$t("message.delete"),
        okButtonColor: "btn-danger",
      });
      if (ok) {
        this.deleting = true;
        try {
          await axios.delete("/api/quotes/" + id);
          this.data = this.data.filter((quote) => quote.id != id);
          this.$toasts.success(this.$t("modules.quotes.deleted"));
        } catch (error) {
          if (is404(error)) {
            this.$toasts.error(this.$t("error.404.default_resource"));
          }
        }
        this.deleting = false;
      }
    },
  },
};
</script>
<style scoped>
tr.no-border td {
  border: 0;
}
</style>