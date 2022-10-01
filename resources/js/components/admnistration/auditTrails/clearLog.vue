<template>
  <range-date
    v-bind:value="rangeDate"
    v-on:input1="rangeDate[0] = $event"
    v-on:input2="rangeDate[1] = $event"
    :urlpost="urlpost"
    @submit="submit"
  ></range-date>
</template>

<script>
export default {
  props: ["date1", "date2", "urlpost"],
  data: function () {
    return {
      rangeDate: [this.date1, this.date2],
    };
  },
  created: function () {
    // this.rangeDate[0] = this.date1;
    // this.rangeDate[1] = this.date2;
  },
  methods: {
    async submit() {
      const newPost = this.rangeDate;
      const token = jwt_encode(newPost, jwtKey);
      const json = await App_template.AjaxSubmitFormPromises(
        this.urlpost,
        token
      );
      if (json.status != "success") {
        toastr.info(json.message);
      } else {
        App_template.response_form_token(json);
      }
    },
  },
};
</script>
