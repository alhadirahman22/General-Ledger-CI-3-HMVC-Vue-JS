<template>
  <widget-form :title="title">
    <div slot="body">
      <div class="form-group row">
        <label for="" class="col-form-label col-md-3"> Code</label>
        <div class="col-md-6">
          <input
            type="text"
            class="form-control"
            name="code"
            disabled
            placeholder="Auto by system"
            v-model="code"
          />
          <input
            type="hidden"
            class="form-control"
            name="reimbursment_id"
            disabled
            placeholder="Auto by system"
            v-model="reimbursment_id"
          />
        </div>
      </div>
      <div class="form-group row">
        <label for="" class="col-form-label col-md-3"> Name</label>
        <div class="col-md-6">
          <input
            type="text"
            class="form-control"
            name="name"
            v-model="name"
            :disabled="disableInput"
          />
        </div>
      </div>
      <div class="form-group row">
        <label for="" class="col-form-label col-md-3"> Transaction Date</label>
        <div class="col-md-6">
          <input
            type="date"
            class="form-control"
            name="date_reimbursment"
            v-model="date_reimbursment"
            :disabled="disableInput"
          />
        </div>
      </div>
      <div class="form-group row">
        <label for="" class="col-form-label col-md-3"> Desc </label>
        <div class="col-md-6">
          <textarea
            name="desc"
            id=""
            rows="4"
            class="form-control"
            :disabled="disableInput"
            v-model="desc"
          ></textarea>
        </div>
      </div>
      <div class="form-group row">
        <label for="klasifikasi_id" class="col-form-label col-md-3">
          Requested by</label
        >
        <div class="col-md-6">
          <Select2
            v-model="requested_by"
            :options="employeeOptions"
            placeholder="Pilih Employee"
            :disabled="disableInput"
          />
        </div>
      </div>
      <div class="form-group row">
        <label for="klasifikasi_id" class="col-form-label col-md-3">
          Prices</label
        >
        <div class="col-md-4">
          <input-currency
            v-model="value"
            :name="'value'"
            :disabled="disableInput"
          ></input-currency>
        </div>
      </div>
    </div>
    <div slot="footerbutton">
      <div class="pull-left">
        <a
          :href="moduledata.module_url"
          class="btn btn-purple"
          v-html="iconbtn.cancel_w_icon"
        >
        </a>
        <button
          type="button"
          class="btn btn-success"
          v-html="iconbtn.save_w_icon"
          :disabled="loadingBtn || disableInput"
          v-show="!disableInput"
          @click="submit"
        ></button>
      </div>
      <div class="pull-right">
        <button
          type="button"
          class="btn btn-success"
          :disabled="loadingBtn || disableInput"
          v-show="action == 'approve'"
          @click="approve"
        >
          Approve
        </button>
        <button
          type="button"
          class="btn btn-danger"
          :disabled="loadingBtn || disableInput"
          v-show="action == 'approve'"
          @click="reject"
        >
          Reject
        </button>
      </div>
    </div>
  </widget-form>
</template>

<script>
import VueSweetalert2 from "vue-sweetalert2";
import "sweetalert2/dist/sweetalert2.min.css";
import Datepicker from "vuejs-datepicker";
Vue.use(VueSweetalert2);
export default {
  props: ["title", "dataprop", "moduledata", "iconbtn"],
  components: {
    Datepicker,
  },
  data: function () {
    return {
      action: "add",
      loadingBtn: false,
      disableInput: false,
      employeeOptions: [],
      requested_by: null,
      code: null,
      name: null,
      date_reimbursment: null,
      desc: null,
      reimbursment_id: null,
      value: null,
    };
  },
  methods: {
    async loadEmployee() {
      const res = await axios(base_url + "administration/api_employee/fetch");
      if (res.data.success) {
        let dataRest = res.data.data;
        let myOptions = [];
        for (let index = 0; index < dataRest.length; index++) {
          const temp = {
            id: dataRest[index].employee_id,
            text: dataRest[index].nip + " - " + dataRest[index].name,
          };
          myOptions.push(temp);
        }
        this.employeeOptions = myOptions;
      }
    },
    async submit() {
      if (confirm("Are you sure ?")) {
        const postData = {
          requested_by: this.requested_by,
          code: this.code,
          date_reimbursment: this.date_reimbursment,
          desc: this.desc,
          reimbursment_id: this.reimbursment_id,
          name: this.name,
          value: this.value,
        };
        App_template.loadingStart();
        try {
          const token = jwt_encode(postData, jwtKey);
          const json = await App_template.AjaxSubmitFormPromises(
            this.moduledata.module_url + "save",
            token
          );
          if (json.status == "success") {
            this.code = json.code;
            this.$swal({
              title: "Code",
              html: "<h4>Your code is <br/>" + json.code + "</h4>",
              type: "success",
              confirmButtonText: "OK",
            }).then(function () {
              App_template.response_form_token(json);
            });
          } else {
            App_template.response_form_token(json);
          }
        } catch (error) {
          console.log(error);
        } finally {
          await App_template.timeout(1000);
          App_template.loadingEnd(0);
        }
      }
    },
    async approve() {},
    async reject() {},
    loadShowData() {
      if (typeof this.dataprop.reimbursment_id != "undefined") {
        this.disableInput = true;
        this.action = "view";
        this.code = this.dataprop.code;
        this.name = this.dataprop.name;
        this.reimbursment_id = this.dataprop.reimbursment_id;
        this.date_reimbursment = this.dataprop.date_reimbursment;
        this.requested_by = this.dataprop.requested_by;
        this.value = this.dataprop.value;
        this.desc = this.dataprop.desc;
        console.log(this.dataprop);
      }
    },
  },
  created() {
    this.loadEmployee();
    this.loadShowData();
  },
};
</script>
