<template>
  <div class="row">
    <div class="col-md-4">
      <input
        type="date"
        placeholder="Input Date"
        class="form-control"
        :value="value[0]"
        v-on:input="$emit('input1', $event.target.value)"
      />
    </div>
    <div class="col-md-4">
      <input
        type="date"
        placeholder="Input Date"
        class="form-control"
        :value="value[1]"
        v-on:input="$emit('input2', $event.target.value)"
      />
    </div>
    <div class="col-md-4" v-show="urlpost !== undefined">
      <button class="btn btn-sx btn-success" @click="submit">Submit</button>
    </div>
  </div>
</template>

<script>
export default {
  props: ["value", "urlpost"],
  methods: {
    submit() {
      this.$emit("submit");
    },
  },
};
</script>
