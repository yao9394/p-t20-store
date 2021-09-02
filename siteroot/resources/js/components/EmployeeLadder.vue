<template>
<div>
    <div class="row mb-2">
        <div class="col">
            <date-picker v-model="dateRange" value-type="format" format="YYYY-MM-DD" range placeholder="Select date range" :clearable="false"></date-picker>
        </div>
        <div class="col">
            <button class="btn btn-success" v-on:click="orderCsv">CSV Export</button>
        </div>
    </div>
    <div class="alert alert-success" role="alert" v-if="success">
        {{message}}
    </div>
    <div class="row justify-content-center align-items-center">
    <table v-if="!loading" class="table table-striped">
        <thead>
          <tr>
            <th scope="col" v-for="key in columns" :key="key">
                {{key}}
            </th>
          </tr>
        </thead>
        <tbody>
            <tr v-for="(employee, index) in employees" :key="employee.id">
                <td>
                    {{index + 1}}
                </td>
                <td>
                    {{employee.name}}
                </td>
                <td>
                    {{employee.sales_count}}
                </td>
            </tr>
        </tbody>
    </table>
    <div class="spinner-border mt-5" role="status" v-if="loading">
        <span class="sr-only">Loading...</span>
    </div>
    </div>
</div>
</template>
<script>
import DatePicker from 'vue2-datepicker';
export default {
    components: { DatePicker},
    data() {
        return {
            dateRange: ['2020-06-01', '2020-06-30'],//date range to be used for chart.
            employees: [],
            loading: false,
            formData: {
                start: '',
                end: ''
            },
            columns: ['#ranking', 'name', 'count'],
            success: false
        }
    },
    created() {
        this.fetchEmployees();
    },
    watch: {
        dateRange: function (val) {
            if (val[0] != null &&  val[1] != null) {
                this.fetchEmployees();
            }
        }
    },
    methods: {
        fetchEmployees: function () {
            this.loading = true;
            this.formData.start = this.dateRange[0]
            this.formData.end = this.dateRange[1]
            axios.post('api/employee/show', this.formData).then(response => {
                this.employees = response.data
                this.loading = false;
            }).catch(error => console.log(error));
        },
        orderCsv: function() {
            this.loading = true;
            this.success = false;
            this.formData.start = this.dateRange[0]
            this.formData.end = this.dateRange[1]
            axios.post('api/employee/csv', this.formData).then(response => {
                this.message = response.data.success;
                if (this.message) {
                  this.success = true
                }
                this.loading = false;
            }).catch(error => console.log(error));
        },
    }
}
</script>