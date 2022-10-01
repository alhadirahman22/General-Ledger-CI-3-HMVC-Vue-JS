<template>
  <div>
    <widget-form :title="title">
      <div slot="body">
        <div class="form-group row">
          <label for="klasifikasi_id" class="col-form-label col-md-3">
            Pilih Benda</label
          >
          <div class="col-md-4">
            <!-- <Select2
              v-model="benda_id"
              :options="bendaOptions"
              placeholder="Pilih Benda"
              :disabled="disableInput"
            /> -->
            <select2-multiple-control
              v-model="benda_id"
              :options="bendaOptions"
              placeholder="Pilih Benda"
              :disabled="disableInput"
            />
          </div>
        </div>
        <div class="form-group row">
          <label for="klasifikasi_id" class="col-form-label col-md-3">
            Pilih Requester</label
          >
          <div class="col-md-4">
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
            Pilih Jenis Mutasi</label
          >
          <div class="col-md-4">
            <Select2
              v-model="jenis_mutasi_id"
              :options="jenisMutasiOptions"
              placeholder="Pilih Jenis Mutasi"
              @select="onSelectJenisMutasi($event)"
              :disabled="disableInput"
            />
          </div>
        </div>
        <div class="form-group row">
          <label class="col-form-label col-md-3"> Reason</label>
          <div class="col-md-4">
            <textarea class="form-control" v-model="reason"></textarea>
          </div>
        </div>
        <div class="row" v-show="dataApproval.length">
          <div style="padding: 10px; margin-bottom: 10px">
            <h5>
              <strong
                >Approval Type for Department :
                {{ approvalTypeJenisMutasi }}</strong
              >
            </h5>
          </div>
          <div
            class="col-md-6"
            v-for="(item, index) in dataApproval"
            :key="index"
          >
            <div class="row">
              <div style="padding: 10px; margin-bottom: 10px">
                <div class="pull-left">
                  <span style="color: red">{{
                    "(" + (index + 1) + ") " + item.departmentData.name
                  }}</span>
                </div>
                <div class="pull-right">
                  <span style="color: blue">
                    Approval Type for Employee :
                    {{ item.type_approval == "1" ? "Series" : "Paralel" }}</span
                  >
                </div>
              </div>
              <div style="margin-bottom: 10px">
                <table class="table">
                  <thead>
                    <tr>
                      <th>No</th>
                      <th>Jabatan</th>
                      <th>Employee</th>
                    </tr>
                  </thead>
                  <tbody>
                    <tr v-for="(item2, index2) in item.approval" :key="index2">
                      <td>{{ index2 + 1 }}</td>
                      <td>{{ item2.jabatanData.name }}</td>
                      <td>{{ item2.employeeData.name }}</td>
                    </tr>
                  </tbody>
                </table>
              </div>
            </div>
          </div>
        </div>
      </div>
      <div slot="footerbutton" v-show="dataApproval.length">
        <div class="pull-left">
          <a
            :href="moduledata.module_url"
            class="btn btn-purple"
            v-html="iconbtn.cancel_w_icon"
          >
          </a>
        </div>
        <div class="pull-right">
          <button
            type="button"
            class="btn btn-success"
            v-html="iconbtn.save_w_icon"
            :disabled="loadingBtn || disableInput"
            v-show="!disableInput"
            @click="submit"
          ></button>
        </div>
      </div>
    </widget-form>
  </div>
</template>

<script>
// import Select2 from "v-select2-component";
// import Select2MultipleControl from "v-select2-multiple-component";
// Vue.component("Select2", Select2);
// Vue.component("Select2MultipleControl", Select2MultipleControl);
export default {
  props: ["title", "dataprop", "moduledata", "iconbtn"],
  data: function () {
    return {
      benda_id: null,
      disableInput: false,
      bendaOptions: [],
      dataApproval: [],
      approvalTypeJenisMutasi: null,
      requested_by: null,
      employeeOptions: [],
      jenis_mutasi_id: null,
      jenisMutasiOptions: [],
      loadingBtn: false,
      reason: null,
    };
  },
  methods: {
    async loadJenisMutasi() {
      const res = await axios(base_url + "mutasi/api_jenis_mutasi");
      if (res.data.success) {
        let dataRest = res.data.data;
        let myOptions = [];
        for (let index = 0; index < dataRest.length; index++) {
          const temp = {
            id: dataRest[index].jenis_mutasi_id,
            text: dataRest[index].museum.name + " - " + dataRest[index].name,
            type_approval: dataRest[index].type_approval,
          };
          myOptions.push(temp);
        }
        this.jenisMutasiOptions = myOptions;
      }
    },
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
          benda_id: this.benda_id,
          jenis_mutasi_id: this.jenis_mutasi_id,
          reason: this.reason,
        };

        App_template.loadingStart();
        try {
          const token = jwt_encode(postData, jwtKey);
          const json = await App_template.AjaxSubmitFormPromises(
            this.moduledata.module_url + "save",
            token
          );
          App_template.response_form_token(json);
        } catch (error) {
          console.log(err);
        } finally {
          await App_template.timeout(1000);
          App_template.loadingEnd(0);
        }
      }
    },
    async onSelectJenisMutasi({ id, text, type_approval }) {
      await this._onJenisMutasi(id);
      this.approvalTypeJenisMutasi =
        type_approval == "1" ? "Series" : "Paralel";
    },
    async _onJenisMutasi(jenis_mutasi_id) {
      const newPost = {
        jenis_mutasi_id: jenis_mutasi_id,
      };

      const token = jwt_encode(newPost, jwtKey);
      const json = await App_template.AjaxSubmitFormPromises(
        base_url + "mutasi/api_mutasi/createLoadApproval",
        token
      );

      if (json.status == "error") {
        toastr.info(json.msg);
      } else {
        this.dataApproval = json.data;
      }
    },
    async loadDataBenda() {
      const res = await axios(base_url + "benda/api_benda");
      if (res.data.success) {
        let dataRest = res.data.data;
        let myOptions = [];
        for (let index = 0; index < dataRest.length; index++) {
          const temp = {
            id: dataRest[index].benda_id,
            text: dataRest[index].name,
          };
          myOptions.push(temp);
        }
        this.bendaOptions = myOptions;
      }
    },
  },
  created() {
    this.loadDataBenda();
    this.loadEmployee();
    this.loadJenisMutasi();
  },
};
</script>
