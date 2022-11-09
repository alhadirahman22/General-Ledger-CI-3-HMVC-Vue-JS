<template>
  <div
    class="modal fade"
    v-bind:id="'modalidtemplate_' + ref_id"
    tabindex="-1"
    role="dialog"
    aria-labelledby="exampleModalLabel"
    aria-hidden="true"
    ref="modalidtemplate"
  >
    <div
      class="modal-dialog modal-lg"
      role="document"
      style="width: calc(100% - 100px)"
    >
      <div class="modal-content">
        <div class="modal-header">
          <div class="pull-left">
            <h5 class="modal-title" id="exampleModalLabel">{{ title }}</h5>
          </div>
          <div class="pull-right">
            <button
              type="button"
              class="close"
              @click="$emit('close')"
              data-dismiss="modal"
              aria-label="Close"
            >
              <span aria-hidden="true">&times;</span>
            </button>
          </div>
        </div>
        <slot name="body"></slot>
        <slot name="footer"></slot>
      </div>
    </div>
  </div>
</template>

<script>
export default {
  props: ["ref_id", "title"],
  data: function () {
    return {
      loading: false,
    };
  },
  created() {},
  mounted() {
    $("#modalidtemplate_" + this.ref_id).modal({
      show: true,
      backdrop: "static",
    });
  },
  methods: {},
};
</script>
