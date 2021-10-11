<template>
  <div v-if="!loading">
    <h3>{{ $t("modules.roles.select_hierarchy") }}</h3>
    <sortable-list lockAxis="y" :value="rolesAvailable" @input="input($event)">
      <sortable-list-element v-for="(item, index) in rolesAvailable" :index="index" :key="index">
        <div class="d-flex justify-content-center align-items-center">
          <span class="badge badge-info badge-pill" v-if="item.selected"><i class="fas fa-asterisk"></i></span>
          <span>{{item.name}}</span>
        </div>
      </sortable-list-element>
    </sortable-list>
  </div>
  <div v-else>
    {{$t("message.loading")}}
  </div>
</template>
<script>
import SortableList from "../SortableList";
import SortableListElement from "../SortableListElement";
export default {
  components: {
    SortableList,
    SortableListElement
  },
  props: {
    rid: null,
  },
  data () {
    return {
      loading: false,
      rolesAvailable: []
    };
  },
  async created () {
    this.loading =  true;
    try {
      const roles = (await axios.get("/api/user/roles-below")).data.data.filter(role => role.hierarchy!=0);
      const NRHQ = roles.length-1;
      for (var i = 0; i<NRHQ; i++){
        this.rolesAvailable.push({
          name: roles[i].name,
          selected: (roles[i].id == this.rid)? true : false
        })
      }
      if (this.rid == null){
        this.rolesAvailable.push({
          name: this.$t("modules.roles.new_role"),
          selected: true
        })
      }
      this.$emit("input", this.calcDistance(this.rolesAvailable));
    } catch (error) {
      this.$toasts.error($t("error.fatal"));
    }
    this.loading = false;
  },
  methods: {
    calcDistance(payload) {
      return this.$store.getters.getUserHierarchy + 1 + payload.findIndex(role => role.selected == true);
    },
    input(payload) {
      this.rolesAvailable = payload;
      this.$emit("input", this.calcDistance(payload));
    }
  }
};
</script>