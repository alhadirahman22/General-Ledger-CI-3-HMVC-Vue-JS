<template>
  <div>
    <div>
      <div class="form-group row">
        <label for="jenis_mutasi_id" class="col-form-label col-md-3">
          NIP</label
        >
        <div class="col-md-4">
          <input
            type="text"
            name="nip"
            value=""
            id="nip"
            label="NIP"
            form_control_class="col-md-4"
            required2="true"
            class="form-control"
            v-model="nip"
          />
        </div>
      </div>
      <div class="form-group row">
        <label for="jenis_mutasi_id" class="col-form-label col-md-3">
          Name</label
        >
        <div class="col-md-4">
          <input
            type="text"
            name="name"
            value=""
            id="name"
            label="Name"
            form_control_class="col-md-4"
            required2="true"
            class="form-control"
            v-model="name"
          />
        </div>
      </div>
      <div class="form-group row">
        <label for="jenis_mutasi_id" class="col-form-label col-md-3">
          Email</label
        >
        <div class="col-md-4">
          <input
            type="text"
            name="email"
            value=""
            id="email"
            label="Email"
            form_control_class="col-md-4"
            required2="true"
            class="form-control"
            v-model="email"
          />
        </div>
      </div>
      <div class="form-group row">
        <label for="jenis_mutasi_id" class="col-form-label col-md-3">
          No Hp</label
        >
        <div class="col-md-4">
          <input
            type="number"
            name="no_hp"
            value=""
            id="no_hp"
            label="No Hp"
            form_control_class="col-md-4"
            required2="true"
            class="form-control"
            v-model="no_hp"
          />
        </div>
      </div>
      <div class="form-group row">
        <label for="gender" class="col-form-label col-md-3"> Gender</label>
        <div class="col-md-4">
          <Select2
            v-model="gender"
            :options="genderOptions"
            placeholder="Pilih Gender"
          />
        </div>
      </div>
      <div class="row">
        <div class="col-xs-12">
          <div class="pull-right" style="margin-bottom: 10px">
            <button
              class="btn btn-sm btn-primary"
              @click="addJabatanDepartment()"
            >
              <i class="ace-icon fa fa fa-plus-circle bigger-110"></i> Add
              Department
            </button>
          </div>
          <table class="table">
            <thead>
              <tr>
                <th>Department</th>
                <th>Jabatan</th>
                <th>*</th>
              </tr>
            </thead>
            <tbody>
              <tr
                v-for="(item, index) in jabatan_department_employee"
                :key="index"
              >
                <td>
                  <Select2
                    v-model="jabatan_department_employee[index].department_id"
                    :options="getDepartmentOptions()"
                    placeholder="Pilih Department"
                    @select="onSelectDepartment($event, index)"
                    @change="onChangeDepartment($event, index)"
                  />
                </td>
                <td>
                  <Select2
                    v-model="jabatan_department_employee[index].jabatan_id"
                    :options="jabatanOptions[index]"
                    placeholder="Pilih jabatan"
                    @select="onSelectJabatan($event, index)"
                  />
                </td>
                <td>
                  <button class="btn btn-danger" @click="deleteDepJab(index)">
                    Delete
                  </button>
                </td>
              </tr>
            </tbody>
          </table>
        </div>
      </div>
    </div>
    <hr />
    <div class="row">
      <div class="col-xs-12">
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
            @click="submit"
          ></button>
        </div>
      </div>
    </div>
  </div>
</template>
<script>
export default {
  props: ["dataemployees", "moduledata", "iconbtn"],
  data: function () {
    return {
      nip: null,
      email: null,
      name: null,
      no_hp: null,
      gender: null,
      genderOptions: [
        { id: "male", text: "Laki-laki" },
        { id: "female", text: "Perempuan" },
      ],
      jabatan_department_employee: [],
      jabatanOptions: [],
      departmentOptions: [],
      employee_id: null,
    };
  },
  created() {
    this.showData();
    this.loadDataDepartment();
    // this.loadDataJabatan();
  },
  watch: {},
  updated() {
    console.log("updated");
  },
  mounted() {},
  computed: {},
  methods: {
    async showData() {
      if (this.dataemployees !== null && this.dataemployees.nip !== undefined) {
        this.nip = this.dataemployees.nip;
        this.email = this.dataemployees.email;
        this.gender = this.dataemployees.gender;
        this.no_hp = this.dataemployees.no_hp;
        this.name = this.dataemployees.name;
        this.employee_id = this.dataemployees.employee_id;

        const depDatEmp = this.dataemployees.department;

        for (let index = 0; index < depDatEmp.length; index++) {
          this.jabatan_department_employee.push({
            department_id: depDatEmp[index].pivot.department_id,
            jabatan_id: depDatEmp[index].pivot.jabatan_id,
          });
          await this.loadDataJabatan(
            depDatEmp[index].pivot.department_id,
            index
          );
        }
      }
    },
    addJabatanDepartment() {
      this.jabatan_department_employee.push({
        department_id: null,
        jabatan_id: null,
      });
    },
    getDepartmentOptions() {
      return this.departmentOptions;
    },

    onSelectDepartment(ev, index) {
      this.loadDataJabatan(ev.id, index);
    },
    onChangeDepartment(id, index) {
      this.loadDataJabatan(id, index);
    },
    onSelectJabatan(ev, index) {},
    async loadDataDepartment() {
      const newPost = {};
      const token = jwt_encode(newPost, jwtKey);
      const json = await App_template.AjaxSubmitFormPromises(
        base_url + "administration/api_department/fetch",
        token
      );
      if (json.success) {
        let dataRest = json.data;
        let myOptions = [];
        for (let index = 0; index < dataRest.length; index++) {
          const temp = {
            id: dataRest[index].department_id,
            text: dataRest[index].company.name + " - " + dataRest[index].name,
          };
          myOptions.push(temp);
        }
        this.departmentOptions = myOptions;
      }
    },
    async loadDataJabatan(department_id, indexDept) {
      const postData = { department_id: department_id };
      const token = jwt_encode(postData, jwtKey);
      const json = await App_template.AjaxSubmitFormPromises(
        base_url + "administration/api_jabatan/jabatanByDept",
        token
      );
      if (json.success) {
        let dataRest = json.data;
        let myOptions = [];
        for (let index = 0; index < dataRest.length; index++) {
          const temp = {
            id: dataRest[index].jabatan_id,
            text: dataRest[index].name,
          };
          myOptions.push(temp);
        }
        this.jabatanOptions[indexDept] = myOptions;
        this.$forceUpdate();
      }
    },
    deleteDepJab(index) {
      this.jabatan_department_employee =
        this.jabatan_department_employee.filter((item, i) => index != i);
    },
    async submit() {
      const dataemployees = {
        nip: this.nip,
        email: this.email,
        name: this.name,
        no_hp: this.no_hp,
        gender: this.gender,
        employee_id: this.employee_id,
      };

      if (confirm("Are you sure ?")) {
        const postData = dataemployees;
        postData.jabatan_department_employee = this.jabatan_department_employee;
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
  },
};
</script>
