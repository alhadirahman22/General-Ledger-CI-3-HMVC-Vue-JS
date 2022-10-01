<template>
  <div>
    <widget-form :title="title">
      <div slot="body">
        <div class="form-group row">
          <label for="klasifikasi_id" class="col-form-label col-md-3">
            Pilih Benda</label
          >
          <div class="col-md-4">
            <Select2
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
            <textarea
              class="form-control"
              v-model="reason"
              :disabled="disableInput"
            ></textarea>
          </div>
        </div>
        <div v-show="dataApproval.length">
          <div style="padding: 10px; margin-bottom: 10px">
            <h5>
              <strong
                >Approval Type for Department :
                {{ approvalTypeJenisMutasi }}</strong
              >
            </h5>
          </div>
          <div class="row">
            <div
              class="col-md-12"
              v-for="(item, index) in dataApproval"
              :key="index"
              style="margin-right: 20px"
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
                      {{
                        item.type_approval == "1" ? "Series" : "Paralel"
                      }}</span
                    >
                  </div>
                </div>
                <div style="margin-bottom: 10px">
                  <table class="table table-striped table-bordered table-hover">
                    <thead>
                      <tr>
                        <th style="text-align: center; width: 10px">No</th>
                        <th style="text-align: center; width: 25%">Employee</th>
                        <th style="text-align: center">Status</th>
                        <th style="text-align: center">Desc</th>
                        <th style="text-align: center">Action</th>
                      </tr>
                    </thead>
                    <tbody>
                      <tr
                        v-for="(item2, index2) in item.approval"
                        :key="index2"
                      >
                        <td>{{ index2 + 1 }}</td>
                        <td>{{ item2.employeeData.name }}</td>
                        <td
                          v-html="setStatus(item2)"
                          style="text-align: center"
                        ></td>
                        <td v-html="setDesc(item2)"></td>
                        <td style="text-align: center">
                          <div v-show="showbtn(item, item2)">
                            <button
                              class="btn btn-primary btn-xs"
                              @click="approve(item2)"
                              :disabled="btnApproval"
                            >
                              Approve
                            </button>
                            <button
                              class="btn btn-danger btn-xs"
                              @click="reject(item2)"
                              :disabled="btnApproval"
                            >
                              Reject
                            </button>
                          </div>
                        </td>
                      </tr>
                    </tbody>
                  </table>
                </div>
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
import Select2 from "v-select2-component";
import Select2MultipleControl from "v-select2-multiple-component";
Vue.component("Select2", Select2);
Vue.component("Select2MultipleControl", Select2MultipleControl);
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
      btnApproval: false,
      reason: null,
    };
  },
  methods: {
    async approve(item2) {
      if (confirm("Are you sure ?")) {
        const token = jwt_encode(item2, jwtKey);
        try {
          this.btnApproval = true;
          const json = await App_template.AjaxSubmitFormPromises(
            base_url + "mutasi/api_mutasi/approve",
            token
          );

          if (json.status == "error") {
            toastr.info(json.msg);
            this.btnApproval = false;
          } else {
            window.location.reload();
          }
        } catch (error) {
          this.btnApproval = false;
          console.log(error);
        }
      }
    },
    async reject(item2) {
      if (confirm("Are you sure ?")) {
        const token = jwt_encode(item2, jwtKey);
        try {
          this.btnApproval = true;
          const json = await App_template.AjaxSubmitFormPromises(
            base_url + "mutasi/api_mutasi/reject",
            token
          );

          if (json.status == "error") {
            toastr.info(json.msg);
            this.btnApproval = false;
          } else {
            window.location.reload();
          }
        } catch (error) {
          this.btnApproval = false;
          console.log(error);
        }
      }
    },
    showbtn(item, item2) {
      if (
        item.condition == "1" &&
        item2.condition == "1" &&
        parseInt(this.moduledata.user.employee_id) == item2.employee_id
      ) {
        return true;
      }

      return false;
    },
    setStatus(item) {
      let color = "";
      switch (item.status) {
        case "0":
          color = "color:blue;";
          break;
        case "1":
          color = "color:green;";
          break;
        case "-1":
          color = "color:red;";
          break;
        case "2":
          color = "color:#fb00ff;";
          break;
        case "-2":
          color = "color:#b326fbd6;";
          break;
        default:
          break;
      }

      return '<span style="' + color + '">' + item.statusShow + "</span>";
    },
    setDesc(item) {
      const log = item.log;
      const le = log.length;
      const dateCreated = moment(item.created_at).format("DD MMM YYYY");
      return (
        "<span>" +
        log[le - 1].desc +
        "<span><br/>" +
        '<span style="color:#5f8b25">' +
        dateCreated +
        "</span>"
      );
    },
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
    async onSelectJenisMutasi(id, text, type_approval) {
      await this._onJenisMutasi(id);
      this.approvalTypeJenisMutasi =
        type_approval == "1" ? "Series" : "Paralel";
    },
    async _onJenisMutasi(jenis_mutasi_id) {
      const newPost = {
        mutasi_benda_id: this.dataprop.mutasi_benda_id,
      };

      const token = jwt_encode(newPost, jwtKey);
      const json = await App_template.AjaxSubmitFormPromises(
        // base_url + "mutasi/api_mutasi/createLoadApproval",
        base_url + "mutasi/api_mutasi/loadApprovalMutasi",
        token
      );

      if (json.status == "error") {
        toastr.info(json.msg);
      } else {
        this.dataApproval = json.data;
      }
    },
    async checkBenda(benda_id, jenis_mutasi_id) {
      const newPost = {
        benda_id: benda_id,
        jenis_mutasi_id: jenis_mutasi_id,
      };

      const token = jwt_encode(newPost, jwtKey);
      const json = await App_template.AjaxSubmitFormPromises(
        base_url + "mutasi/api_mutasi/createCheckBenda",
        token
      );

      return json;
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
    loadShowData() {
      this.benda_id = this.dataprop.benda_id;
      this.jenis_mutasi_id = this.dataprop.jenis_mutasi_id;
      this.requested_by = this.dataprop.requested_by;
      this.reason = this.dataprop.reason;

      this.onSelectJenisMutasi(this.jenis_mutasi_id);
      this.disableInput = true;
    },
  },
  async created() {
    await this.loadDataBenda();
    await this.loadEmployee();
    this.loadJenisMutasi();
    this.loadShowData();
  },
};
</script>
