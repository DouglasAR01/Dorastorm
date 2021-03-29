<template>
  <div class="card">
    <div class="card-body">
      <h5 class="card-title">{{ $t("modules.roles.description") }}</h5>
      <div class="row border rounded">
        <div class="col-8">
          <p class="card-text text-muted">
            <span v-if="description"> {{ description }}</span>
            <span v-else> {{ $t("message.default_description") }}</span>
          </p>
        </div>
        <div class="col-4">
          <p class="text-muted">{{ $t("message.created_at") }} {{created | humanDate}}</p>
          <p class="text-muted">{{ $t("message.updated_at") }} {{modified | humanDate}}</p>
        </div>
      </div>
      <h5 class="card-title mt-1">{{ $t("modules.roles.role_permissions") }}</h5>
      <div class="row mb-2 mt-3" v-for="row in rows" :key="'r' + row">
        <div
          class="col d-flex align-items-stretch justify-content-center"
          v-for="(content, categorie) in elementsInRow(row)"
          :key="categorie"
        >
          <div class="card mb-2">
            <div class="card-header">{{ content[0] }}</div>
            <ul class="list-group list-group-flush">
              <li
                class="list-group-item"
                v-for="permission in content[1]"
                :key="permission"
              >
                {{ permission }}
              </li>
            </ul>
          </div>
        </div>
        <div
          class="col d-flex align-items-stretch"
          v-for="(filler, index) in fillerElements(elementsInRow(row))"
          :key="'f' + index"
        ></div>
      </div>
    </div>
  </div>
</template>
<script>
import ItemArrangement from "../../shared/mixins/item-arrangement";
import RoleService from "../../services/role-permissions";
export default {
  mixins: [ItemArrangement],
  props: {
    payload: Object,
  },
  data() {
    return {
      id: this.payload.id,
      name: this.payload.name,
      description: this.payload.description,
      items: Object.entries(
        RoleService.categorizePermissions(this.payload.permissions)
      ),
      created: this.payload.created,
      modified: this.payload.modified,
    };
  },
};
</script>