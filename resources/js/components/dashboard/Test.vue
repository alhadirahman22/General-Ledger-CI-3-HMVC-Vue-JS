<template>
  <div class="row">
    <div class="col-md-12">
      <button class="btn btn-primary" @click="testfunction">Click</button>
    </div>
    <range-date
      v-bind:value="rangeDate"
      v-on:input1="rangeDate[0] = $event"
      v-on:input2="rangeDate[1] = $event"
      :urlpost="'post'"
    ></range-date>
  </div>
</template>

<script>
export default {
  data: function () {
    return {
      name: "",
      store: "",
      prefer_date_label: "",
      prefer_slot: "",
      sending: false,
      status_store: "",
      rangeDate: ["2022-01-01", "2022-03-03"],
    };
  },
  created: function () {},
  methods: {
    testfunction: function () {
      alert("testfunction");
    },
  },
};
</script>
