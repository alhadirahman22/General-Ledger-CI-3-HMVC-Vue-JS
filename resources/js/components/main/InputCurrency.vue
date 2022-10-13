<template>
  <div>
    <input
      class="form-control"
      :name="name"
      :value="value"
      @input="$emit('update', $event.target.value)"
      ref="input"
      type="number"
      v-on:keyup="numberingFormat"
      :disabled="disabled"
    />
    <span style="color: red">{{ amount }}</span>
  </div>
</template>

<script>
export default {
  props: ["value", "name", "disabled"],
  model: {
    prop: "value",
    event: "update",
  },
  data: function () {
    return {
      amount: null,
    };
  },
  onMounted() {
    const input = this.$refs.input;
    if (input.value.hasAttribute("autofocus")) {
      input.value.focus();
    }
  },
  created() {
    if (this.value != "" && this.value != undefined) {
      this.amount = App_template.formatRupiah(this.value);
    }
  },
  methods: {
    numberingFormat(e) {
      const v = e.target.value;
      this.amount = App_template.formatRupiah(v);
    },
  },
};
</script>
